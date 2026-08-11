<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogService;

use App\Models\Product;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected CategoryRepositoryInterface $categoryRepository,
        protected SupplierRepositoryInterface $supplierRepository,
        protected StockTransactionRepositoryInterface $stockTransactionRepository,
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Menampilkan daftar produk.
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');

        $products = $this->productRepository
            ->search($keyword, 10);

        $products->getCollection()->transform(function ($product) {
            $product->currentStock = $this->stockTransactionRepository
                ->getCurrentStock($product->id);

            return $product;
        });

        return view('products.index', compact('products'));
    }

    /**
     * Menampilkan detail produk.
     */
    public function show(int $id)
    {
        $product = $this->productRepository->find($id);

        $currentStock = $this->stockTransactionRepository
            ->getCurrentStock($product->id);

        $stockHistory = $this->stockTransactionRepository
            ->getByProduct($product->id);

        return view('products.show', compact(
            'product',
            'currentStock',
            'stockHistory'
        ));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create()
    {
        return view('products.create', [
            'categories' => $this->categoryRepository->all(),
            'suppliers' => $this->supplierRepository->all(),
        ]);
    }

    /**
     * Menyimpan produk baru dan mencatat stok awal jika ada.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'name' => ['required', 'max:255'],
            'sku' => ['required', 'unique:products,sku'],
            'description' => ['nullable'],
            'purchase_price' => ['required', 'numeric'],
            'selling_price' => ['required', 'numeric'],
            'image' => ['nullable', 'image', 'max:2048'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'initial_stock' => ['required', 'integer', 'min:0'], // Validasi Stok Awal
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Pisahkan initial_stock agar tidak error saat disimpan ke tabel products
        $initialStock = $validated['initial_stock'];
        unset($validated['initial_stock']);

        // Simpan data produk
        $product = $this->productRepository->create($validated);

        // Jika terdapat stok awal > 0, otomatis buatkan transaksi penerimaan stok awal
        if ($initialStock > 0) {
            $this->stockTransactionRepository->create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => 'Masuk',
                'quantity'   => $initialStock,
                'date'       => now()->toDateString(),
                'status'     => 'Diterima',
                'notes'      => 'Stok awal pendaftaran produk baru',
            ]);
        }

        $this->activityLogService->log(
            action: 'created',
            description: 'Menambahkan produk "' . $product->name . '" dengan stok awal ' . $initialStock . '.',
            subjectType: 'Product',
            subjectId: $product->id,
            properties: [
                'name' => $product->name,
                'sku' => $product->sku,
                'category_id' => $product->category_id,
                'supplier_id' => $product->supplier_id,
                'purchase_price' => $product->purchase_price,
                'selling_price' => $product->selling_price,
                'minimum_stock' => $product->minimum_stock,
                'initial_stock' => $initialStock,
            ]
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(int $id)
    {
        return view('products.edit', [
            'product' => $this->productRepository->find($id),
            'categories' => $this->categoryRepository->all(),
            'suppliers' => $this->supplierRepository->all(),
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:2048',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));

            return redirect()->route('products.index')->with('success', 'Data produk berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    /**
     * Export Data Produk ke CSV (Standard Native PHP)
     */
    public function export(): StreamedResponse
    {
        $fileName = 'data-produk-' . date('Y-m-d') . '.csv';

        // Ambil data produk dari database/repository
        $products = Product::with(['category', 'supplier'])->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Header Kolom CSV
            fputcsv($file, [
                'ID',
                'Nama Produk',
                'SKU',
                'Kategori',
                'Supplier',
                'Harga Beli',
                'Harga Jual',
                'Stok Minimum',
                'Deskripsi'
            ]);

            // Isi Data Produk
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->category?->name ?? '-',
                    $product->supplier?->name ?? '-',
                    $product->purchase_price,
                    $product->selling_price,
                    $product->minimum_stock,
                    $product->description,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Mengupdate produk.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'name' => ['required', 'max:255'],
            'sku' => [
                'required',
                Rule::unique('products')->ignore($id),
            ],
            'description' => ['nullable'],
            'purchase_price' => ['required', 'numeric'],
            'selling_price' => ['required', 'numeric'],
            'image' => ['nullable', 'image', 'max:2048'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ]);

        $product = $this->productRepository->find($id);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $this->productRepository->update($id, $validated);

        $this->activityLogService->log(
            action: 'updated',
            description: 'Memperbarui produk "' . $validated['name'] . '".',
            subjectType: 'Product',
            subjectId: $product->id,
            properties: [
                'changes' => $validated,
            ]
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk.
     */
    public function destroy(int $id)
    {
        $product = $this->productRepository->find($id);

        // Hapus file gambar dari storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $this->productRepository->delete($id);

        $this->activityLogService->log(
            action: 'deleted',
            description: 'Menghapus produk "' . $product->name . '".',
            subjectType: 'Product',
            subjectId: $product->id,
            properties: [
                'name' => $product->name,
                'sku' => $product->sku,
            ]
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}