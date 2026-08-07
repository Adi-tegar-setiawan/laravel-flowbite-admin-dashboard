<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;

class StockTransactionService
{
    public function __construct(
        protected StockTransactionRepositoryInterface $transactionRepository,
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * Membuat transaksi stok baru.
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            // Pastikan produk ada
            $this->productRepository->find($data['product_id']);

            // Validasi stok jika transaksi keluar
            if ($data['type'] === 'Keluar') {

                $currentStock = $this->transactionRepository
                    ->getCurrentStock($data['product_id']);

                if ($data['quantity'] > $currentStock) {
                    throw new Exception(
                        'Stok tidak mencukupi untuk melakukan transaksi.'
                    );
                }
            }

            return $this->transactionRepository->create($data);
        });
    }

    /**
     * Mengubah transaksi.
     */
    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            return $this->transactionRepository
                ->update($id, $data);

        });
    }

    /**
     * Menghapus transaksi.
     */
    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {

            return $this->transactionRepository
                ->delete($id);

        });
    }
}