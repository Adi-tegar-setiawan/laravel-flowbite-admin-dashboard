<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StockTransactionService;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use App\Services\ActivityLogService;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class StockTransactionController extends Controller
{
    public function __construct(
        protected StockTransactionRepositoryInterface $transactionRepository,
        protected ProductRepositoryInterface $productRepository,
        protected SupplierRepositoryInterface $supplierRepository,
        protected StockTransactionService $transactionService,
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Menampilkan daftar transaksi.
     */
    public function index()
    {
        $transactions = $this->transactionRepository->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan form transaksi.
     */
    public function create()
    {
        $products = $this->productRepository->all();
        $suppliers = $this->supplierRepository->all();

        return view('transactions.create', compact('products', 'suppliers'));
    }

    /**
     * Menyimpan transaksi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'  => ['required', 'exists:products,id'],
            'type'        => ['required', 'in:Masuk,Keluar'],
            'quantity'    => ['required', 'integer', 'min:1'],
            'date'        => ['required', 'date'],
            'status'      => ['required', 'in:Pending,Diterima,Ditolak,Dikeluarkan'],
            'notes'       => ['nullable'],
        ]);

        if (
            $validated['type'] === 'Keluar'
            && $validated['status'] !== 'Dikeluarkan'
        ) {
            throw ValidationException::withMessages([
                'status' => 'Transaksi barang keluar harus memiliki status Dikeluarkan.',
            ]);
        }

        if (
            $validated['type'] === 'Masuk'
            && $validated['status'] === 'Dikeluarkan'
        ) {
            throw ValidationException::withMessages([
                'status' => 'Transaksi barang masuk tidak dapat memiliki status Dikeluarkan.',
            ]);
        }

        $validated['user_id'] = auth()->id();

        $this->transactionService->create($validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(int $id)
    {
        return view('transactions.edit', [
            'transaction' => $this->transactionRepository->find($id),
            'products'    => $this->productRepository->all(),
            'suppliers'   => $this->supplierRepository->all(),
        ]);
    }

    /**
     * Mengupdate transaksi.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'product_id'  => ['required', 'exists:products,id'],
            'type'        => ['required', 'in:Masuk,Keluar'],
            'quantity'    => ['required', 'integer', 'min:1'],
            'date'        => ['required', 'date'],
            'status'      => ['required', 'in:Pending,Diterima,Ditolak,Dikeluarkan'],
            'notes'       => ['nullable'],
        ]);

        if (
            $validated['type'] === 'Keluar'
            && $validated['status'] !== 'Dikeluarkan'
        ) {
            throw ValidationException::withMessages([
                'status' => 'Transaksi barang keluar harus memiliki status Dikeluarkan.',
            ]);
        }

        if (
            $validated['type'] === 'Masuk'
            && $validated['status'] === 'Dikeluarkan'
        ) {
            throw ValidationException::withMessages([
                'status' => 'Transaksi barang masuk tidak dapat memiliki status Dikeluarkan.',
            ]);
        }

        $this->transactionService->update($id, $validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus transaksi.
     */
    public function destroy(int $id)
    {
        $this->transactionService->delete($id);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Memperbarui status transaksi (Konfirmasi Terima / Konfirmasi Keluar oleh Staff Gudang).
     */
    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Pending', 'Diterima', 'Ditolak', 'Dikeluarkan'])],
        ]);

        // Ambil data transaksi menggunakan $this->transactionRepository
        $transaction = $this->transactionRepository->find($id);

        // Update status transaksi melalui repository
        $this->transactionRepository->update($id, [
            'status' => $validated['status'],
        ]);

        // Catat Log Aktivitas
        $this->activityLogService->log(
            action: 'updated_status',
            description: 'Mengonfirmasi status transaksi ' . $transaction->type . ' produk "' . ($transaction->product?->name ?? '-') . '" menjadi ' . $validated['status'] . '.',
            subjectType: 'StockTransaction',
            subjectId: $transaction->id,
            properties: [
                'previous_status' => $transaction->status,
                'new_status'      => $validated['status'],
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Status transaksi berhasil dikonfirmasi.');
    }
}