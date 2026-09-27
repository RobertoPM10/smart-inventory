<?php

namespace App\Providers;

use App\Domain\Repositories\ProductRepositoryInterface;
use App\Infrastructure\Repositories\EloquentProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\SaleRepositoryInterface;
use App\Infrastructure\Repositories\EloquentSaleRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
        );
        // Vincula la interfaz del repositorio de ventas con la implementacion concreta
        $this->app->bind(
            SaleRepositoryInterface::class,
            EloquentSaleRepository::class
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
