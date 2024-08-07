<?php

namespace App\Repositories\Interfaces;

interface SalesRepositoryInterface extends BaseResourceRepositoryInterface
{
    /**
     * Get total gross sales on the given date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function getTotalGrossSales(string $startDate, string $endDate): float;

    /**
     * Get total sales count on the given date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public function getSalesCount(string $startDate, string $endDate): int;
}
