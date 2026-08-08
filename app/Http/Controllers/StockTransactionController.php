<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StockTransactionService;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use Illuminate\Validation\ValidationException;

class StockTransactionController extends Controller
{
    public function __construct(
        protected StockTransactionRepositoryInterface $transactionRepository,
        protected ProductRepositoryInterface $productRepository,
        protected StockTransactionService $transactionService
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

        return view('transactions.create', compact('products'));
    }

    /**
     * Menyimpan transaksi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:Masuk,Keluar'],
            'quantity' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date'],
            'status' => ['required', 'in:Pending,Diterima,Ditolak,Dikeluarkan'],
            'notes' => ['nullable'],
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
            'products' => $this->productRepository->all(),
        ]);
    }

    /**
     * Mengupdate transaksi.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:Masuk,Keluar'],
            'quantity' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date'],
            'status' => ['required', 'in:Pending,Diterima,Ditolak,Dikeluarkan'],
            'notes' => ['nullable'],
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
}