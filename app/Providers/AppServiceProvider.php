<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Repositories\Interfaces\WarehouseRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Repository interface => implementation bindings.
     * Setiap modul (Tahap 8-18) menambahkan baris baru di sini.
     */
    protected array $repositories = [
        CategoryRepositoryInterface::class => CategoryRepository::class,
        SupplierRepositoryInterface::class => SupplierRepository::class,
        WarehouseRepositoryInterface::class => WarehouseRepository::class,
        ProductRepositoryInterface::class => ProductRepository::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
