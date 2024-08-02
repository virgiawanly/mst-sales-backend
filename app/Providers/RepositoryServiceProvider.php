<?php

namespace App\Providers;

use App\Repositories\BarangRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\BarangRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use App\Repositories\SalesRepository;
use App\Services\SalesService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(BarangRepositoryInterface::class, BarangRepository::class);
        $this->app->bind(SalesRepositoryInterface::class, SalesRepository::class);

        // Bind services
        $this->app->bind(SalesService::class, function ($app) {
            return new SalesService($app->make(SalesRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
