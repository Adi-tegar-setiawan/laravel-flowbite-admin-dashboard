<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Services\ActivityLogService;

/*
|--------------------------------------------------------------------------
| Guest Routes (Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Semua User Terautentikasi)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| 1. KHUSUS ADMIN (Full Control, Master Data, Audit, & Settings)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {

    // Manajemen Pengguna (CRUD Users)
    Route::resource('users', UserController::class)->except(['show']);

    // Manajemen Kategori (CRUD Categories)
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Manajemen Supplier (CRUD Supplier - Add, Edit, Delete)
    Route::resource('suppliers', SupplierController::class)->except(['index', 'show']);

    // Produk (Khusus Edit & Hapus Master Produk)
    Route::resource('products', ProductController::class)->only(['edit', 'update', 'destroy']);

    // Atribut Produk (Khusus Admin)
    Route::post('/products/{productId}/attributes', [ProductAttributeController::class, 'store'])->name('products.attributes.store');
    Route::get('/products/{productId}/attributes/{attributeId}/edit', [ProductAttributeController::class, 'edit'])->name('products.attributes.edit');
    Route::put('/products/{productId}/attributes/{attributeId}', [ProductAttributeController::class, 'update'])->name('products.attributes.update');
    Route::delete('/products/{productId}/attributes/{attributeId}', [ProductAttributeController::class, 'destroy'])->name('products.attributes.destroy');

    // Pengaturan Umum Aplikasi (Settings)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Laporan Aktivitas Pengguna (Activity Log)
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');

    // Route khusus import & export produk
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');

    // Route Testing Activity Log
    Route::get('/test-activity-log', function (ActivityLogService $activityLogService) {
        $activityLogService->log(
            action: 'test',
            description: 'Admin melakukan test Activity Log.',
            properties: ['source' => 'manual-test']
        );
        return 'Activity Log berhasil dibuat.';
    });
});

/*
|--------------------------------------------------------------------------
| 2. ADMIN + MANAJER GUDANG (Master Data, Laporan, Input Transaksi & Opname)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin,Manajer Gudang'])->group(function () {

    // View Master Supplier
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');

    // Produk (View, Detail, Tambah Produk Baru, & Edit Produk)
    Route::resource('products', ProductController::class)->except(['destroy']);

    // Pencatatan Transaksi Barang Masuk & Keluar (Halaman Draf & Form Transaksi)
    Route::resource('transactions', StockTransactionController::class)->except(['show']);

    // Stock Opname (Rekonsiliasi & Penyesuaian Stok)
    Route::resource('stock-opnames', StockOpnameController::class)->except(['show']);

    // Laporan Stok & Transaksi
    Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
    Route::get('/reports/transactions', [ReportController::class, 'transactionReport'])->name('reports.transactions');
});

/*
|--------------------------------------------------------------------------
| 3. OPERASIONAL STAFF GUDANG & SEMUA ROLE (Aksi Konfirmasi Status Transaksi)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin,Manajer Gudang,Staff Gudang'])->group(function () {

    // Route Konfirmasi Status Transaksi (Dipakai Staff Gudang di Dashboard)
    Route::patch('/transactions/{id}/update-status', [StockTransactionController::class, 'updateStatus'])
        ->name('transactions.update-status');
});