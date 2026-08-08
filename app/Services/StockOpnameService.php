<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\StockOpnameRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;

class StockOpnameService
{
    public function __construct(
        protected StockOpnameRepositoryInterface $opnameRepository,
        protected ProductRepositoryInterface $productRepository,
        protected StockTransactionRepositoryInterface $stockTransactionRepository
    ) {
    }

    /**
     * Membuat stock opname baru.
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            // Pastikan produk tersedia
            $this->productRepository->find($data['product_id']);

            // Ambil stok sistem saat opname dibuat
            $systemStock = $this->stockTransactionRepository
                ->getCurrentStock($data['product_id']);

            // Hitung selisih stok
            $difference =
                $data['physical_stock'] - $systemStock;

            $data['system_stock'] = $systemStock;
            $data['difference'] = $difference;

            return $this->opnameRepository->create($data);
        });
    }

    /**
     * Mengubah stock opname.
     */
    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            // Ambil opname lama
            $opname = $this->opnameRepository->find($id);

            // Pastikan produk tersedia
            $this->productRepository->find($data['product_id']);

            /*
             * Jika produk tidak berubah,
             * pertahankan system_stock lama.
             */
            if ($opname->product_id == $data['product_id']) {

                $systemStock = $opname->system_stock;

            } else {

                /*
                 * Jika produk diganti,
                 * ambil stok sistem dari produk baru.
                 */
                $systemStock = $this->stockTransactionRepository
                    ->getCurrentStock($data['product_id']);
            }

            $difference =
                $data['physical_stock'] - $systemStock;

            $data['system_stock'] = $systemStock;
            $data['difference'] = $difference;

            return $this->opnameRepository->update(
                $id,
                $data
            );
        });
    }

    /**
     * Menghapus stock opname.
     */
    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {

            // Pastikan data memang ada
            $this->opnameRepository->find($id);

            return $this->opnameRepository->delete($id);
        });
    }
}