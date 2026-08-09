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
     * Menyimpan produk.
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
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = $this->productRepository->create($validated);

        $this->activityLogService->log(
            action: 'created',
            description: 'Menambahkan produk "' . $product->name . '".',
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