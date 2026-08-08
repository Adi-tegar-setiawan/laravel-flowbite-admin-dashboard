<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use App\Services\ActivityLogService;

class DashboardService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected StockTransactionRepositoryInterface $transactionRepository,
        protected ActivityLogService $activityLogService
    ) {
    }

    public function getDashboardData(): array
    {
        return [
            'totalProducts' => $this->productRepository->count(),

            'todayTransactions' => $this->transactionRepository->countToday(),

            'stockInToday' => $this->transactionRepository->countStockInToday(),

            'stockOutToday' => $this->transactionRepository->countStockOutToday(),

            'lowStockProducts' => $this->productRepository->getLowStockProducts(),

            'transactionChart' => $this->transactionRepository
                ->getTransactionChartData(7),

            'recentTransactions' => $this->transactionRepository
                ->getRecentTransactions(5),

            'recentActivities' => $this->activityLogService->latest(10),
        ];
    }
}