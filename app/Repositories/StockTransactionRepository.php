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

    /**
     * Mengambil data transaksi dengan paginasi dan filter.
     */
    public function paginate(int $perPage = 10, array $filters = [])
    {
        $query = StockTransaction::with(['product', 'user'])->latest();

        // Filter Pencarian Nama Produk atau SKU
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter Tipe Transaksi (Masuk / Keluar)
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Filter Tanggal
        if (!empty($filters['start_date'])) {
            $query->whereDate('date', '>=', $filters['start_date']);
        }

        return $query->paginate($perPage);
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

    public function getByProduct(int $productId)
    {
        return StockTransaction::with('user')
            ->where('product_id', $productId)
            ->latest('date')
            ->latest('id')
            ->get();
    }

    public function countToday(): int
    {
        return StockTransaction::whereDate(
            'date',
            Carbon::today()
        )->count();
    }

    public function countStockInToday(): int
    {
        return StockTransaction::whereDate('date', today())
            ->where('type', 'Masuk')
            ->where('status', 'Diterima')
            ->sum('quantity');
    }

    public function countStockOutToday(): int
    {
        return StockTransaction::whereDate('date', today())
            ->where('type', 'Keluar')
            ->where('status', 'Dikeluarkan')
            ->sum('quantity');
    }

    public function getTransactionChartData(int $days = 7)
    {
        $startDate = Carbon::today()->subDays($days - 1);

        $transactions = StockTransaction::whereDate(
            'date',
            '>=',
            $startDate
        )
            ->where(function ($query) {
                $query
                    ->where(function ($q) {
                        $q->where('type', 'Masuk')
                            ->where('status', 'Diterima');
                    })
                    ->orWhere(function ($q) {
                        $q->where('type', 'Keluar')
                            ->where('status', 'Dikeluarkan');
                    });
            })
            ->get();

        $data = [];

        for ($i = 0; $i < $days; $i++) {

            $date = $startDate->copy()->addDays($i);

            $stockIn = $transactions
                ->filter(function ($transaction) use ($date) {
                    return $transaction->date->isSameDay($date)
                        && $transaction->type === 'Masuk'
                        && $transaction->status === 'Diterima';
                })
                ->sum('quantity');

            $stockOut = $transactions
                ->filter(function ($transaction) use ($date) {
                    return $transaction->date->isSameDay($date)
                        && $transaction->type === 'Keluar'
                        && $transaction->status === 'Dikeluarkan';
                })
                ->sum('quantity');

            $data[] = [
                'date' => $date->format('d M'),
                'stockIn' => $stockIn,
                'stockOut' => $stockOut,
            ];
        }

        return $data;
    }

    public function getRecentTransactions(int $limit = 5)
    {
        return StockTransaction::with([
            'user',
            'product'
        ])
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Mengambil data laporan transaksi berdasarkan tanggal dan tipe.
     */
    public function getReport(string $startDate, string $endDate, ?string $type = null)
    {
        $query = StockTransaction::with(['product', 'user'])
            ->whereBetween('date', [
                $startDate,
                $endDate
            ]);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->latest('date')->latest('id')->get();
    }
}