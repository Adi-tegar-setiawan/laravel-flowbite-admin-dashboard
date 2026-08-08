<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\StockTransaction;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;

class StockTransactionRepository implements StockTransactionRepositoryInterface
{
    public function all()
    {
        return StockTransaction::with([
            'product',
            'user'
        ])->latest()->get();
    }

    public function paginate(int $perPage = 10)
    {
        return StockTransaction::with([
            'product',
            'user'
        ])->latest()->paginate($perPage);
    }

    public function find(int $id)
    {
        return StockTransaction::with([
            'product',
            'user'
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        return StockTransaction::create($data);
    }

    public function update(int $id, array $data)
    {
        $transaction = $this->find($id);

        $transaction->update($data);

        return $transaction;
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }

    public function getCurrentStock(int $productId): int
    {
        $stockIn = StockTransaction::where('product_id', $productId)
            ->where('type', 'Masuk')
            ->where('status', 'Diterima')
            ->sum('quantity');

        $stockOut = StockTransaction::where('product_id', $productId)
            ->where('type', 'Keluar')
            ->where('status', 'Dikeluarkan')
            ->sum('quantity');

        return $stockIn - $stockOut;
    }

    public function countToday(): int
    {
        return StockTransaction::whereDate(
            'date',
            Carbon::today()
        )->count();
    }
}