<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Sales;
use App\Observers\CustomerObserver;
use App\Observers\SalesObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sales::observe(SalesObserver::class);
        Customer::observe(CustomerObserver::class);
    }
}
