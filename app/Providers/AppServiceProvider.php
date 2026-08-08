<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StockTransactionRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProductAttributeRepository;

use App\Repositories\Interfaces\ProductAttributeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use App\Repositories\Interfaces\SupplierRepositoryInterface;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            SupplierRepositoryInterface::class,
            SupplierRepository::class
        );

        $this->app->bind(
            StockTransactionRepositoryInterface::class,
            StockTransactionRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            ProductAttributeRepositoryInterface::class,
            ProductAttributeRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
