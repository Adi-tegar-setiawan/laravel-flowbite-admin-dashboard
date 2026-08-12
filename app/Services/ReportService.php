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
     * Mengambil data laporan stok produk (Filter Kategori & Periode Tanggal)
     * beserta kalkulasi stok fisik berjalan saat ini.
     */
    public function getStockReport(array $filters = [])
    {
        // 1. Memanggil method getStockReport di ProductRepository
        $products = $this->productRepository->getStockReport($filters);

        // 2. Hitung current_stock dinamis dari transaksi (Masuk 'Diterima' - Keluar 'Dikeluarkan')
        // Jika $products berbentuk LengthAwarePaginator atau Collection
        if (method_exists($products, 'getCollection')) {
            $products->getCollection()->transform(function ($product) {
                $product->current_stock = $this->transactionRepository
                    ->getCurrentStock($product->id);
                return $product;
            });
        } elseif (method_exists($products, 'transform')) {
            $products->transform(function ($product) {
                $product->current_stock = $this->transactionRepository
                    ->getCurrentStock($product->id);
                return $product;
            });
        } else {
            foreach ($products as $product) {
                $product->current_stock = $this->transactionRepository
                    ->getCurrentStock($product->id);
            }
        }

        return $products;
    }

    /**
     * Mengambil data laporan transaksi berdasarkan tanggal dan tipe.
     */
    public function getTransactionReport(string $startDate, string $endDate, ?string $type = null)
    {
        return $this->transactionRepository->getReport($startDate, $endDate, $type);
    }
}