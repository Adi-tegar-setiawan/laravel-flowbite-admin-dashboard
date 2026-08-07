<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\SupplierRepositoryInterface;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierRepositoryInterface $supplierRepository
    ) {
    }

    /**
     * Menampilkan daftar supplier.
     */
    public function index()
    {
        $suppliers = $this->supplierRepository->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Menampilkan form tambah supplier.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Menyimpan supplier baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'max:255'],
            'address' => ['nullable'],
            'phone'   => ['nullable', 'max:50'],
            'email'   => ['nullable', 'email'],
        ]);

        $this->supplierRepository->create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit supplier.
     */
    public function edit(int $id)
    {
        $supplier = $this->supplierRepository->find($id);

        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Mengupdate supplier.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'    => ['required', 'max:255'],
            'address' => ['nullable'],
            'phone'   => ['nullable', 'max:50'],
            'email'   => ['nullable', 'email'],
        ]);

        $this->supplierRepository->update($id, $validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Menghapus supplier.
     */
    public function destroy(int $id)
    {
        $this->supplierRepository->delete($id);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}