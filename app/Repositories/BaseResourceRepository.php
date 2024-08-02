<?php

namespace App\Repositories;

use App\Models\BaseModel;
use App\Repositories\Interfaces\BaseResourceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseResourceRepository implements BaseResourceRepositoryInterface
{
    /**
     * The model instance.
     *
     * @var \App\Models\BaseModel
     */
    protected BaseModel $model;

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
            ->search($search)
            ->searchColumns($queryParams)
            ->ofOrder($sortBy, $sortOrder)
            ->paginate($perPage);
    }

    /**
     * Get a resource by id.
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new resource.
     *
     * @param  array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a resource.
     *
     * @param  int $id
     * @param  array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $data): Model
    {
        $resource = $this->model->findOrFail($id);
        $resource->update($data);

        return $resource;
    }

    /**
     * Delete a resource.
     *
     * @param  int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $resource = $this->model->findOrFail($id);

        return $resource->delete();
    }
}
