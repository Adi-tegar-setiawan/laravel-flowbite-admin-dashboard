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
     * Menyimpan transaksi (Input Barang Masuk / Barang Keluar oleh Manajer Gudang).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type'       => ['required', 'in:Masuk,Keluar'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'date'       => ['required', 'date'],
            'status'     => ['required', 'in:Pending,Diterima,Ditolak,Dikeluarkan'],
            'notes'      => ['nullable', 'string'],
        ]);

        // Validasi Logis: Barang Masuk tidak boleh diset langsung 'Dikeluarkan'
        if ($validated['type'] === 'Masuk' && $validated['status'] === 'Dikeluarkan') {
            throw ValidationException::withMessages([
                'status' => 'Transaksi barang masuk tidak dapat memiliki status Dikeluarkan.',
            ]);
        }

        // Validasi Logis: Barang Keluar tidak boleh diset 'Diterima'
        if ($validated['type'] === 'Keluar' && $validated['status'] === 'Diterima') {
            throw ValidationException::withMessages([
                'status' => 'Transaksi barang keluar tidak dapat memiliki status Diterima.',
            ]);
        }

        $validated['user_id'] = auth()->id();

        $this->transactionService->create($validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi stok berhasil dicatat.');
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
            'product_id' => ['required', 'exists:products,id'],
            'type'       => ['required', 'in:Masuk,Keluar'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'date'       => ['required', 'date'],
            'status'     => ['required', 'in:Pending,Diterima,Ditolak,Dikeluarkan'],
            'notes'      => ['nullable', 'string'],
        ]);

        if ($validated['type'] === 'Masuk' && $validated['status'] === 'Dikeluarkan') {
            throw ValidationException::withMessages([
                'status' => 'Transaksi barang masuk tidak dapat memiliki status Dikeluarkan.',
            ]);
        }

        if ($validated['type'] === 'Keluar' && $validated['status'] === 'Diterima') {
            throw ValidationException::withMessages([
                'status' => 'Transaksi barang keluar tidak dapat memiliki status Diterima.',
            ]);
        }

        $this->transactionService->update($id, $validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Data transaksi berhasil diperbarui.');
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
     * Memperbarui status transaksi (Aksi Konfirmasi oleh Staff Gudang di Dashboard).
     */
    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Pending', 'Diterima', 'Ditolak', 'Dikeluarkan'])],
        ]);

        $transaction = $this->transactionRepository->find($id);

        $this->transactionRepository->update($id, [
            'status' => $validated['status'],
        ]);

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