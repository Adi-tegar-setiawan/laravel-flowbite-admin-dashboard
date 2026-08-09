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
        protected StockTransactionRepositoryInterface $stockTransactionRepository,
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Membuat stock opname baru.
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            // Pastikan produk tersedia
            $product = $this->productRepository->find(
                $data['product_id']
            );

            // Ambil stok sistem saat opname dibuat
            $systemStock = $this->stockTransactionRepository
                ->getCurrentStock($data['product_id']);

            // Hitung selisih stok
            $difference =
                $data['physical_stock'] - $systemStock;

            // Simpan hasil perhitungan
            $data['system_stock'] = $systemStock;
            $data['difference'] = $difference;

            // Buat stock opname
            $opname = $this->opnameRepository->create($data);

            // Activity Log
            $this->activityLogService->log(
                'created',
                'Membuat stock opname untuk produk "' .
                $product->name .
                '"',
                'StockOpname',
                $opname->id,
                [
                    'product_id' => $opname->product_id,
                    'system_stock' => $opname->system_stock,
                    'physical_stock' => $opname->physical_stock,
                    'difference' => $opname->difference,
                    'date' => $opname->date,
                    'notes' => $opname->notes,
                ]
            );

            return $opname;
        });
    }

    /**
     * Mengubah stock opname.
     */
    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            // Ambil stock opname lama
            $opname = $this->opnameRepository->find($id);

            // Simpan data lama untuk Activity Log
            $oldData = [
                'product_id' => $opname->product_id,
                'system_stock' => $opname->system_stock,
                'physical_stock' => $opname->physical_stock,
                'difference' => $opname->difference,
                'date' => $opname->date,
                'notes' => $opname->notes,
            ];

            // Pastikan produk baru tersedia
            $product = $this->productRepository->find(
                $data['product_id']
            );

            /*
             * Jika produk tidak berubah,
             * gunakan system_stock lama.
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

            // Hitung ulang selisih
            $difference =
                $data['physical_stock'] - $systemStock;

            // Simpan hasil perhitungan
            $data['system_stock'] = $systemStock;
            $data['difference'] = $difference;

            // Update stock opname
            $updatedOpname = $this->opnameRepository->update(
                $id,
                $data
            );

            // Activity Log
            $this->activityLogService->log(
                'updated',
                'Memperbarui stock opname untuk produk "' .
                $product->name .
                '"',
                'StockOpname',
                $id,
                [
                    'old' => $oldData,

                    'new' => [
                        'product_id' => $updatedOpname->product_id,
                        'system_stock' => $updatedOpname->system_stock,
                        'physical_stock' => $updatedOpname->physical_stock,
                        'difference' => $updatedOpname->difference,
                        'date' => $updatedOpname->date,
                        'notes' => $updatedOpname->notes,
                    ],
                ]
            );

            return $updatedOpname;
        });
    }

    /**
     * Menghapus stock opname.
     */
    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {

            // Ambil data sebelum dihapus
            $opname = $this->opnameRepository->find($id);

            // Ambil produk
            $product = $this->productRepository->find(
                $opname->product_id
            );

            // Simpan data opname untuk Activity Log
            $opnameData = [
                'product_id' => $opname->product_id,
                'system_stock' => $opname->system_stock,
                'physical_stock' => $opname->physical_stock,
                'difference' => $opname->difference,
                'date' => $opname->date,
                'notes' => $opname->notes,
            ];

            // Hapus stock opname
            $this->opnameRepository->delete($id);

            // Activity Log
            $this->activityLogService->log(
                'deleted',
                'Menghapus stock opname untuk produk "' .
                $product->name .
                '"',
                'StockOpname',
                $id,
                $opnameData
            );

            return $opname;
        });
    }
}