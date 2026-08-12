<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use App\Services\ActivityLogService;

class StockTransactionService
{
    public function __construct(
        protected StockTransactionRepositoryInterface $transactionRepository,
        protected ProductRepositoryInterface $productRepository,
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Mengambil daftar transaksi stok berpaginasi beserta filter.
     */
    public function getPaginatedTransactions(array $filters = [], int $perPage = 10)
    {
        return $this->transactionRepository->paginate($perPage, $filters);
    }

    /**
     * Membuat transaksi stok baru.
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            // Pastikan produk ada
            $this->productRepository->find($data['product_id']);

            // Transaksi keluar yang benar-benar dikeluarkan
            // tidak boleh melebihi stok tersedia.
            if (
                $data['type'] === 'Keluar'
                && $data['status'] === 'Dikeluarkan'
            ) {
                $currentStock = $this->transactionRepository
                    ->getCurrentStock($data['product_id']);

                if ($data['quantity'] > $currentStock) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Stok tidak mencukupi untuk melakukan transaksi.',
                    ]);
                }
            }

            $transaction = $this->transactionRepository->create($data);

            $this->activityLogService->log(
                'CREATED',
                'Membuat transaksi stok ' .
                $transaction->type .
                ' untuk produk ' .
                ($transaction->product?->name ?? '#' . $transaction->product_id) .
                ' sebanyak ' .
                $transaction->quantity,
                'StockTransaction',
                $transaction->id,
                [
                    'product_id' => $transaction->product_id,
                    'type' => $transaction->type,
                    'quantity' => $transaction->quantity,
                    'status' => $transaction->status,
                ]
            );

            return $transaction;
        });
    }

    /**
     * Mengubah transaksi stok.
     */
    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            // Ambil transaksi lama
            $oldTransaction = $this->transactionRepository->find($id);

            // Pastikan produk baru ada
            $this->productRepository->find($data['product_id']);

            /*
             * Jika transaksi lama mempengaruhi stok,
             * keluarkan dulu efek transaksi lama dari stok.
             */
            $oldEffect = $this->getStockEffect(
                $oldTransaction->type,
                $oldTransaction->status,
                $oldTransaction->quantity
            );

            /*
             * Jika product_id tidak berubah:
             *
             * current stock masih mengandung transaksi lama.
             * Maka kita kembalikan efek transaksi lama terlebih dahulu.
             */
            if ($oldTransaction->product_id == $data['product_id']) {

                $availableStock =
                    $this->transactionRepository
                        ->getCurrentStock($data['product_id'])
                    - $oldEffect;

            } else {

                /*
                 * Jika product berubah, stok product baru
                 * tidak mengandung transaksi lama.
                 */
                $availableStock =
                    $this->transactionRepository
                        ->getCurrentStock($data['product_id']);
            }

            /*
             * Transaksi keluar baru harus diperiksa
             * terhadap stok yang tersedia setelah transaksi lama
             * dikeluarkan dari perhitungan.
             */
            if (
                $data['type'] === 'Keluar'
                && $data['status'] === 'Dikeluarkan'
            ) {
                if ($data['quantity'] > $availableStock) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Stok tidak mencukupi untuk transaksi keluar.',
                    ]);
                }
            }

            $transaction = $this->transactionRepository->update($id, $data);

            $this->activityLogService->log(
                'UPDATED',
                'Memperbarui transaksi stok #' . $id,
                'StockTransaction',
                $id,
                [
                    'product_id' => $data['product_id'],
                    'type' => $data['type'],
                    'quantity' => $data['quantity'],
                    'status' => $data['status'],
                ]
            );

            return $transaction;
        });
    }

    /**
     * Menghapus transaksi stok.
     */
    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {

            $transaction = $this->transactionRepository->find($id);

            $this->transactionRepository->delete($id);

            $this->activityLogService->log(
                'DELETE',
                'Menghapus transaksi stok #' . $id,
                'StockTransaction',
                $id,
                [
                    'product_id' => $transaction->product_id,
                    'type' => $transaction->type,
                    'quantity' => $transaction->quantity,
                    'status' => $transaction->status,
                ]
            );

            return $transaction;
        });
    }

    /**
     * Menghitung efek transaksi terhadap stok.
     */
    private function getStockEffect(
        string $type,
        string $status,
        int $quantity
    ): int {
        if ($type === 'Masuk' && $status === 'Diterima') {
            return $quantity;
        }

        if ($type === 'Keluar' && $status === 'Dikeluarkan') {
            return -$quantity;
        }

        return 0;
    }
}