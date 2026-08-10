<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReportService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected StockTransactionRepositoryInterface $transactionRepository,
        protected CategoryRepositoryInterface $categoryRepository
    ) {
    }

    /**
     * Mengambil semua kategori untuk dropdown filter.
     */
    public function getCategories(): Collection
    {
        return $this->categoryRepository->all();
    }

    /**
     * Mengambil data laporan stok produk (Filter Kategori & Periode Tanggal).
     */
    public function getStockReport(array $filters = [])
    {
        // Memanggil method getStockReport di ProductRepository
        return $this->productRepository->getStockReport($filters);
    }

    /**
     * Mengambil data laporan transaksi berdasarkan tanggal dan tipe.
     */
    public function getTransactionReport(string $startDate, string $endDate, ?string $type = null)
    {
        return $this->transactionRepository->getReport($startDate, $endDate, $type);
    }
}