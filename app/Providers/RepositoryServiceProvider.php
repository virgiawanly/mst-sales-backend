<?php

namespace App\Providers;

use App\Repositories\BarangRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\BarangRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
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

        // Bind services
        // ...
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
