<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {
    }

    /**
     * Menampilkan Halaman Laporan Stok Barang.
     */
    public function stockReport(Request $request): View
    {
        $filters = [
            'category_id' => $request->input('category_id'),
            'start_date'  => $request->input('start_date'),
            'end_date'    => $request->input('end_date'),
        ];

        // 1. Ambil Kategori lewat ReportService
        $categories = $this->reportService->getCategories();

        // 2. Ambil Data Laporan Stok lewat ReportService
        $products = $this->reportService->getStockReport($filters);

        return view('reports.stock', compact('products', 'categories', 'filters'));
    }

    /**
     * Menampilkan Halaman Laporan Transaksi Barang (Masuk/Keluar).
     */
    public function transactionReport(Request $request): View
    {
        // Default periode: awal bulan ini s/d hari ini
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());
        $type      = $request->input('type'); // 'Masuk', 'Keluar', atau null (semua)

        $transactions = $this->reportService->getTransactionReport($startDate, $endDate, $type);

        return view('reports.transaction', [
            'transactions' => $transactions,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'selectedType' => $type,
        ]);
    }
}