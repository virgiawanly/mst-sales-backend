<?php

namespace App\Observers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        $this->_removeCustomersAnalyticsCache();
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        $this->_removeCustomersAnalyticsCache();
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        $this->_removeCustomersAnalyticsCache();
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        $this->_removeCustomersAnalyticsCache();
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        $this->_removeCustomersAnalyticsCache();
    }

    /**
     * Remove analytics cache related to customers.
     *
     * @return void
     */
    protected function _removeCustomersAnalyticsCache()
    {
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');

        $mobileAnalyticsCacheKey = 'mobile_dashboard_analytics_' . $startDate . '_' . $endDate;
        $mobileAnalyticslastGeneratedCacheKey = 'mobile_dashboard_analytics_' . $startDate . '_' . $endDate . '_last_generated';

        Cache::forget($mobileAnalyticsCacheKey);
        Cache::forget($mobileAnalyticslastGeneratedCacheKey);
    }
}
