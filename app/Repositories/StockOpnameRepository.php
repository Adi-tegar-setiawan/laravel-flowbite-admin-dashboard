<?php

namespace App\Repositories;

use App\Models\StockOpname;
use App\Repositories\Interfaces\StockOpnameRepositoryInterface;

class StockOpnameRepository implements StockOpnameRepositoryInterface
{
    public function all()
    {
        return StockOpname::with(['product', 'user'])->latest()->get();
    }

    /**
     * Mengambil data stock opname dengan paginasi dan filter.
     */
    public function paginate(int $perPage = 10, array $filters = [])
    {
        $query = StockOpname::with(['product', 'user'])->latest();

        // Filter Pencarian Nama Produk / SKU
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter Rentang Tanggal
        if (!empty($filters['start_date'])) {
            $query->whereDate('date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('date', '<=', $filters['end_date']);
        }

        return $query->paginate($perPage);
    }

    public function find(int $id)
    {
        return StockOpname::with(['product', 'user'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return StockOpname::create($data);
    }

    public function update(int $id, array $data)
    {
        $opname = $this->find($id);
        $opname->update($data);

        return $opname;
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}