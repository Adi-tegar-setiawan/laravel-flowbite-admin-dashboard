<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected CategoryRepositoryInterface $categoryRepository,
        protected SupplierRepositoryInterface $supplierRepository
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

        return view('products.index', compact('products'));
    }

    /**
     * Menampilkan detail produk.
     */
    public function show(int $id)
    {
        $product = $this->productRepository->find($id);

        return view('products.show', compact('product'));
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

        $this->productRepository->create($validated);

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

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}