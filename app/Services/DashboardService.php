<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;

class DashboardService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected StockTransactionRepositoryInterface $transactionRepository
    ) {
    }

    public function getDashboardData(): array
    {
        return [
            'totalProducts' => $this->productRepository->count(),

            'todayTransactions' => $this->transactionRepository->countToday(),

            'lowStockProducts' => $this->productRepository->getLowStockProducts(),
        ];
    }
}