<?php

namespace App\Repositories;

use App\Models\Barang;
use App\Repositories\Interfaces\BarangRepositoryInterface;

class BarangRepository extends BaseResourceRepository implements BarangRepositoryInterface
{
    /**
     * Create a new instance of the repository.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Barang();
    }
}
