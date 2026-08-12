<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Services\ActivityLogService;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierRepositoryInterface $supplierRepository,
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Menampilkan daftar supplier.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $suppliers = $this->supplierRepository->search($search, 10);

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

        $supplier = $this->supplierRepository->create($validated);

        // Activity Log
        $this->activityLogService->log(
            action: 'CREATE',
            description: 'Menambahkan supplier baru "' . $supplier->name . '"',
            subjectType: 'Supplier',
            subjectId: $supplier->id,
            properties: [
                'name' => $supplier->name,
                'address' => $supplier->address,
                'phone' => $supplier->phone,
                'email' => $supplier->email,
            ]
        );

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

        // Ambil data sebelum update
        $supplier = $this->supplierRepository->find($id);

        $oldData = [
            'name' => $supplier->name,
            'address' => $supplier->address,
            'phone' => $supplier->phone,
            'email' => $supplier->email,
        ];

        $this->supplierRepository->update($id, $validated);

        // Activity Log
        $this->activityLogService->log(
            action: 'UPDATE',
            description: 'Mengubah supplier "' . $supplier->name . '"',
            subjectType: 'Supplier',
            subjectId: $supplier->id,
            properties: [
                'old' => $oldData,
                'new' => [
                    'name' => $validated['name'],
                    'address' => $validated['address'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'email' => $validated['email'] ?? null,
                ],
            ]
        );

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Menghapus supplier.
     */
    public function destroy(int $id)
    {
        // Ambil data sebelum dihapus
        $supplier = $this->supplierRepository->find($id);

        $supplierData = [
            'name' => $supplier->name,
            'address' => $supplier->address,
            'phone' => $supplier->phone,
            'email' => $supplier->email,
        ];

        $this->supplierRepository->delete($id);

        // Activity Log
        $this->activityLogService->log(
            action: 'DELETE',
            description: 'Menghapus supplier "' . $supplier->name . '"',
            subjectType: 'Supplier',
            subjectId: $supplier->id,
            properties: $supplierData
        );

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}