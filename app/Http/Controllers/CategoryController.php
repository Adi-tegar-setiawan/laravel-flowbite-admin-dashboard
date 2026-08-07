<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {
    }

    /**
     * Menampilkan daftar kategori.
     */
    public function index()
    {
        $categories = $this->categoryRepository->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Menampilkan form tambah kategori.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Menyimpan kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
        ]);

        $this->categoryRepository->create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit kategori.
     */
    public function edit(int $id)
    {
        $category = $this->categoryRepository->find($id);

        return view('categories.edit', compact('category'));
    }

    /**
     * Memperbarui kategori.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
        ]);

        $this->categoryRepository->update($id, $validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(int $id)
    {
        $this->categoryRepository->delete($id);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}