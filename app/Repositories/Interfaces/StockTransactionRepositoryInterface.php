<?php

namespace App\Repositories\Interfaces;

interface StockTransactionRepositoryInterface
{
    public function all();

    public function paginate(int $perPage = 10);

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function getCurrentStock(int $productId): int;

    public function getByProduct(int $productId);

    public function countToday(): int;

    public function countStockInToday(): int;

    public function countStockOutToday(): int;

    public function getTransactionChartData(int $days = 7);

    public function getRecentTransactions(int $limit = 5);
}