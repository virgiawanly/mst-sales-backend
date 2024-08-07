<?php

namespace App\Repositories\Interfaces;

interface CustomerRepositoryInterface extends BaseResourceRepositoryInterface
{
    /**
     * Get total new customers count on the given date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public function getNewCustomersCount(string $startDate, string $endDate): int;
}
