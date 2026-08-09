<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\ActivityLogService;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
        protected ActivityLogService $activityLogService
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

        $category = $this->categoryRepository->create($validated);

        // Activity Log
        $this->activityLogService->log(
            action: 'CREATE',
            description: 'Menambahkan kategori baru "' . $category->name . '"',
            subjectType: 'Category',
            subjectId: $category->id,
            properties: [
                'name' => $category->name,
                'description' => $category->description,
            ]
        );

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

        // Ambil data sebelum update
        $category = $this->categoryRepository->find($id);

        $oldData = [
            'name' => $category->name,
            'description' => $category->description,
        ];

        $this->categoryRepository->update($id, $validated);

        // Activity Log
        $this->activityLogService->log(
            action: 'UPDATE',
            description: 'Mengubah kategori "' . $category->name . '"',
            subjectType: 'Category',
            subjectId: $category->id,
            properties: [
                'old' => $oldData,
                'new' => [
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                ],
            ]
        );

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(int $id)
    {
        // Ambil data sebelum dihapus
        $category = $this->categoryRepository->find($id);

        $categoryData = [
            'name' => $category->name,
            'description' => $category->description,
        ];

        $this->categoryRepository->delete($id);

        // Activity Log
        $this->activityLogService->log(
            action: 'DELETE',
            description: 'Menghapus kategori "' . $category->name . '"',
            subjectType: 'Category',
            subjectId: $category->id,
            properties: $categoryData
        );

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}