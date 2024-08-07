<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository extends BaseResourceRepository implements CustomerRepositoryInterface
{
    /**
     * Create a new instance of the repository.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Customer();
    }

    /**
     * Get total new customers count on the given date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public function getNewCustomersCount(string $startDate, string $endDate): int
    {
        return $this->model
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }
}
