<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use App\Services\ActivityLogService;
use App\Models\StockTransaction;

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
        // 1. Data Barang Masuk yang Perlu Diperiksa (Status Pending & Type Masuk)
        $pendingStockIn = StockTransaction::with(['product.supplier'])
            ->where('type', 'Masuk')
            ->where('status', 'Pending')
            ->latest()
            ->get();

        // 2. Data Barang Keluar yang Perlu Disiapkan (Status Pending & Type Keluar)
        $pendingStockOut = StockTransaction::with(['product.supplier'])
            ->where('type', 'Keluar')
            ->where('status', 'Pending')
            ->latest()
            ->get();

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

            // Data Khusus Dashboard Staff Gudang
            'pendingStockIn'  => $pendingStockIn,
            'pendingStockOut' => $pendingStockOut,
        ];
    }
}