<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseResourceRepositoryInterface
{
    /**
     * Get all resources.
     *
     * @param  array $queryParams
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(array $queryParams = []): Collection;

    /**
     * Get all resources with pagination.
     *
     * @param  int $perPage
     * @param  array $queryParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginatedList(int $perPage, array $queryParams = []): LengthAwarePaginator;

    /**
     * Get a resource by id.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(int $id): Model;

    /**
     * Create a new resource.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(array $data): Model;

    /**
     * Update a resource.
     *
     * @param int $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a resource.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
