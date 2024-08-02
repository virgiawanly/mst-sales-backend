<?php

namespace App\Repositories;

use App\Models\Sales;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SalesRepository extends BaseResourceRepository implements SalesRepositoryInterface
{
    /**
     * Create a new instance of the repository.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Sales();
    }

    /**
     * Get all resources.
     *
     * @param  array $queryParams
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(array $queryParams = []): Collection
    {
        $search = $queryParams['search'] ?? '';
        $sortBy = $queryParams['sort'] ?? '';
        $order = $queryParams['order'] ?? 'asc';
        $sortOrder = (str_contains($order, 'asc') ? 'asc' : 'desc') ?? '';

        return $this->model
            ->with(['customer'])
            ->search($search)
            ->searchColumns($queryParams)
            ->ofOrder($sortBy, $sortOrder)
            ->get();
    }

    /**
     * Get all resources with pagination.
     *
     * @param int $perPage
     * @param array $queryParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginatedList(int $perPage, array $queryParams = []): LengthAwarePaginator
    {
        $search = $queryParams['search'] ?? '';
        $sortBy = $queryParams['sort'] ?? '';
        $order = $queryParams['order'] ?? 'asc';
        $sortOrder = (str_contains($order, 'asc') ? 'asc' : 'desc') ?? '';

        return $this->model
            ->with(['customer'])
            ->search($search)
            ->searchColumns($queryParams)
            ->ofOrder($sortBy, $sortOrder)
            ->paginate($perPage);
    }
}
