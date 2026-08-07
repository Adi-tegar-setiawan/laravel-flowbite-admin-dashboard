<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::with([
            'category',
            'supplier',
            'attributes'
        ])->latest()->get();
    }

    public function paginate(int $perPage = 10)
    {
        return Product::with([
            'category',
            'supplier',
            'attributes'
        ])->latest()->paginate($perPage);
    }

    public function search(?string $keyword, int $perPage = 10)
    {
        return Product::with([
                'category',
                'supplier',
                'attributes'
            ])
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                      ->orWhere('sku', 'like', "%{$keyword}%");
            })
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id)
    {
        return Product::with([
            'category',
            'supplier',
            'attributes'
        ])->findOrFail($id);
    }

    public function count(): int
    {
        return Product::count();
    }

    public function getLowStockProducts()
    {
        return Product::with('stockTransactions')
            ->get()
            ->filter(function ($product) {

                $stockIn = $product->stockTransactions
                    ->where('type', 'Masuk')
                    ->where('status', 'Diterima')
                    ->sum('quantity');

                $stockOut = $product->stockTransactions
                    ->where('type', 'Keluar')
                    ->where('status', 'Dikeluarkan')
                    ->sum('quantity');

                return ($stockIn - $stockOut) <= $product->minimum_stock;
            });
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(int $id, array $data)
    {
        $product = $this->find($id);

        $product->update($data);

        return $product;
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}