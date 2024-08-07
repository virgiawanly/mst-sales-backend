<?php

namespace App\Services;

use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AnalyticService
{
    /**
     * Get cached mobile dashboard analytics or generate new one.
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public static function getMobileDashboardAnalytics(string $startDate, string $endDate): array
    {
        $analytics = [];
        $lastGenerated = null;

        $dataCacheKey = 'mobile_dashboard_analytics_' . $startDate . '_' . $endDate;
        $lastGeneratedCacheKey = 'mobile_dashboard_analytics_' . $startDate . '_' . $endDate . '_last_generated';

        // Check for cached analytics
        if (Cache::has($dataCacheKey)) {
            $analytics = Cache::get($dataCacheKey);
            $lastGenerated = Cache::get($lastGeneratedCacheKey);
        } else {
            // Generate new analytics
            $analytics = self::generateMobileDashboardAnalytics($startDate, $endDate);

            // Get last generated
            $lastGenerated = Carbon::now();

            // Save cache for 1 hour
            Cache::put($dataCacheKey, $analytics, now()->addHour());
            Cache::put($lastGeneratedCacheKey, $lastGenerated, now()->addHour());
        }

        return [
            'analytics' => $analytics,
            'last_generated' => $lastGenerated
        ];
    }

    /**
     * Generate new mobile dashboard analytics.
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public static function generateMobileDashboardAnalytics(string $startDate, string $endDate): array
    {
        // Prepare repositories
        $salesRepository = app()->make(SalesRepositoryInterface::class);
        $customerRepository = app()->make(CustomerRepositoryInterface::class);

        // Get total gross sales
        $totalGrossSales = $salesRepository->getTotalGrossSales($startDate, $endDate);

        // Get total sales count
        $salesCount = $salesRepository->getSalesCount($startDate, $endDate);

        // Get sales average
        $salesAverage = $salesCount > 0 ? $totalGrossSales / $salesCount : 0;

        // Get total new customers count
        $newCustomersCount = $customerRepository->getNewCustomersCount($startDate, $endDate);

        // Return analytics
        return [
            'total_gross_sales' => $totalGrossSales,
            'sales_count' => $salesCount,
            'sales_average' => $salesAverage,
            'new_customers_count' => $newCustomersCount
        ];
    }
}
