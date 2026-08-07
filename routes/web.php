<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockTransactionController;


/*
|--------------------------------------------------------------------------
| Guest
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
| Authenticated
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

Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::resource('users', UserController::class)->except(['show']);

    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::resource('suppliers', SupplierController::class)->except(['show']);

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

    Route::resource('products', ProductController::class)->except(['show']);

});

/*
|--------------------------------------------------------------------------
| Admin + Manajer + Staff
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Admin,Manajer Gudang,Staff Gudang'
])->group(function () {

    Route::resource(
        'transactions',
        StockTransactionController::class
    )->except(['show']);

});
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::name('index-practice')->get('/', function () {
//     return view('pages.practice.index');
// });

// Route::name('practice.')->group(function () {
//     Route::name('first')->get('practice/1', function () {
//         return view('pages.practice.1');
//     });
//     Route::name('second')->get('practice/2', function () {
//         return view('pages.practice.2');
//     });
// });
