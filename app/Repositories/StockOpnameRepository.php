<?php

namespace App\Repositories;

use App\Models\StockOpname;
use App\Repositories\Interfaces\StockOpnameRepositoryInterface;

class StockOpnameRepository implements StockOpnameRepositoryInterface
{
    /**
     * Mengambil seluruh data stock opname.
     */
    public function all()
    {
        return StockOpname::with([
            'product',
            'user',
        ])
            ->latest('date')
            ->get();
    }

    /**
     * Mengambil data stock opname dengan pagination.
     */
    public function paginate(int $perPage = 10)
    {
        return StockOpname::with([
            'product',
            'user',
        ])
            ->latest('date')
            ->paginate($perPage);
    }

    /**
     * Mengambil satu stock opname berdasarkan ID.
     */
    public function find(int $id)
    {
        return StockOpname::with([
            'product',
            'user',
        ])->findOrFail($id);
    }

    /**
     * Membuat stock opname baru.
     */
    public function create(array $data)
    {
        return StockOpname::create($data);
    }

    /**
     * Mengubah stock opname.
     */
    public function update(int $id, array $data)
    {
        $opname = $this->find($id);

        $opname->update($data);

        return $opname;
    }

    /**
     * Menghapus stock opname.
     */
    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }

    /**
     * Mengambil riwayat stock opname berdasarkan produk.
     */
    public function getByProduct(int $productId)
    {
        return StockOpname::with([
            'product',
            'user',
        ])
            ->where('product_id', $productId)
            ->latest('date')
            ->get();
    }
}