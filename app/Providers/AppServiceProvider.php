<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StockTransactionRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProductAttributeRepository;
use App\Repositories\StockOpnameRepository;
use App\Repositories\ActivityLogRepository;
use App\Repositories\SettingRepository;

use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Interfaces\ActivityLogRepositoryInterface;
use App\Repositories\Interfaces\StockOpnameRepositoryInterface;
use App\Repositories\Interfaces\ProductAttributeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use App\Repositories\Interfaces\SupplierRepositoryInterface;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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

        $this->app->bind(
            StockOpnameRepositoryInterface::class,
            StockOpnameRepository::class
        );

        $this->app->bind(
            ActivityLogRepositoryInterface::class,
            ActivityLogRepository::class
        );

        $this->app->bind(
            SettingRepositoryInterface::class, 
            SettingRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan data pengaturan aplikasi ke seluruh View Blade secara global
        if (Schema::hasTable('settings')) {
            View::composer('*', function ($view) {
                $settingRepository = app(SettingRepositoryInterface::class);
                $settings = $settingRepository->getAllAsKeyValue();

                $view->with('appSettings', [
                    'app_name' => $settings['app_name'] ?? 'Stockify',
                    'app_logo' => isset($settings['app_logo']) ? asset('storage/' . $settings['app_logo']) : null,
                    'company_name' => $settings['company_name'] ?? 'Stockify Warehouse',
                    'company_email' => $settings['company_email'] ?? 'admin@stockify.com',
                    'company_phone' => $settings['company_phone'] ?? '-',
                ]);
            });
        }
    }
}