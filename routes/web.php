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
use App\Services\ActivityLogService;
use App\Http\Controllers\SettingController;


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Admin'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class)
        ->except(['show']);


    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::resource('categories', CategoryController::class)
        ->except(['show']);


    /*
    |--------------------------------------------------------------------------
    | Suppliers - Admin CRUD
    |--------------------------------------------------------------------------
    */

    Route::resource('suppliers', SupplierController::class)
        ->except(['show']);


    /*
    |--------------------------------------------------------------------------
    | Products - Admin Update/Delete
    |--------------------------------------------------------------------------
    */

    Route::resource('products', ProductController::class)
        ->only([
            'edit',
            'update',
            'destroy'
        ]);

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    Route::get('/settings', [SettingController::class, 'index'])
        ->name('settings.index');

    Route::put('/settings', [SettingController::class, 'update'])
        ->name('settings.update');

    /*
    |--------------------------------------------------------------------------
    | Product Attributes
    |--------------------------------------------------------------------------
    */

    Route::post('/products/{productId}/attributes', [
        ProductAttributeController::class,
        'store'
    ])->name('products.attributes.store');

    Route::delete('/products/{productId}/attributes/{attributeId}', [
        ProductAttributeController::class,
        'destroy'
    ])->name('products.attributes.destroy');

    Route::get('/products/{productId}/attributes/{attributeId}/edit', [
        ProductAttributeController::class,
        'edit'
    ])->name('products.attributes.edit');

    Route::put('/products/{productId}/attributes/{attributeId}', [
        ProductAttributeController::class,
        'update'
    ])->name('products.attributes.update');

    Route::get('/test-activity-log', function (ActivityLogService $activityLogService) {
        $activityLogService->log(
            action: 'test',
            description: 'Admin melakukan test Activity Log.',
            properties: [
                'source' => 'manual-test',
            ]
        );

        return 'Activity Log berhasil dibuat.';
    });

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index');

    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])
        ->name('activity-logs.show');


    /*
    |--------------------------------------------------------------------------
    | Transactions - Admin Full Access
    |--------------------------------------------------------------------------
    */
    Route::resource('stock-opnames', StockOpnameController::class)
        ->except(['show']);

    Route::resource('transactions', StockTransactionController::class)
        ->except(['show']);

});


/*
|--------------------------------------------------------------------------
| Admin + Manajer Gudang
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Admin,Manajer Gudang'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get('/reports/stock', [ReportController::class, 'stockReport'])
        ->name('reports.stock');

    Route::get('/reports/transactions', [ReportController::class, 'transactionReport'])
        ->name('reports.transactions');


    /*
    |--------------------------------------------------------------------------
    | Suppliers - View Only
    |--------------------------------------------------------------------------
    */

    Route::get('/suppliers', [SupplierController::class, 'index'])
        ->name('suppliers.index');


    /*
    |--------------------------------------------------------------------------
    | Products - View & Create
    |--------------------------------------------------------------------------
    */

    Route::resource('products', ProductController::class)
        ->only([
            'index',
            'create',
            'store',
            'show'
        ]);

});


/*
|--------------------------------------------------------------------------
| Admin + Manajer Gudang + Staff Gudang
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Admin,Manajer Gudang,Staff Gudang'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Transactions - View
    |--------------------------------------------------------------------------
    */

    Route::get('/transactions', [
        StockTransactionController::class,
        'index'
    ])->name('transactions.index');

});


/*
|--------------------------------------------------------------------------
| Manajer Gudang
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Manajer Gudang'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Transactions - Create
    |--------------------------------------------------------------------------
    */

    Route::get('/transactions/create', [
        StockTransactionController::class,
        'create'
    ])->name('transactions.create');

    Route::post('/transactions', [
        StockTransactionController::class,
        'store'
    ])->name('transactions.store');

});