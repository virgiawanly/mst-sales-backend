<?php

namespace App\Observers;

use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SalesObserver
{
    /**
     * Handle the Sales "created" event.
     */
    public function created(Sales $sales): void
    {
        $this->_removeSalesAnalyticsCache();
    }

    /**
     * Handle the Sales "updated" event.
     */
    public function updated(Sales $sales): void
    {
        $this->_removeSalesAnalyticsCache();
    }

    /**
     * Handle the Sales "deleted" event.
     */
    public function deleted(Sales $sales): void
    {
        $this->_removeSalesAnalyticsCache();
    }

    /**
     * Handle the Sales "restored" event.
     */
    public function restored(Sales $sales): void
    {
        $this->_removeSalesAnalyticsCache();
    }

    /**
     * Handle the Sales "force deleted" event.
     */
    public function forceDeleted(Sales $sales): void
    {
        $this->_removeSalesAnalyticsCache();
    }

    /**
     * Remove analytics cache related to sales.
     *
     * @return void
     */
    protected function _removeSalesAnalyticsCache()
    {
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');

        $mobileAnalyticsCacheKey = 'mobile_dashboard_analytics_' . $startDate . '_' . $endDate;
        $mobileAnalyticslastGeneratedCacheKey = 'mobile_dashboard_analytics_' . $startDate . '_' . $endDate . '_last_generated';

        Cache::forget($mobileAnalyticsCacheKey);
        Cache::forget($mobileAnalyticslastGeneratedCacheKey);
    }
}
