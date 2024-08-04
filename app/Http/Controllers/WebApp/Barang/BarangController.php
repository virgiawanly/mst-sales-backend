<?php

namespace App\Http\Controllers\WebApp\Barang;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\BaseResourceController;
use App\Http\Requests\WebApp\Barang\CreateBarangRequest;
use App\Http\Requests\WebApp\Barang\UpdateBarangRequest;
use App\Repositories\BarangRepository;
use Exception;

class BarangController extends BaseResourceController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected BarangRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WebApp\Barang\CreateBarangRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBarangRequest $request)
    {
        try {
            $result = $this->service->save($request->validated());
            return ResponseHelper::success(trans('messages.successfully_created'), $result, 201);
        } catch (Exception $e) {
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\WebApp\Barang\UpdateBarangRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBarangRequest $request, int $id)
    {
        try {
            $result = $this->service->patch($id, $request->validated());
            return ResponseHelper::success(trans('messages.successfully_updated'), $result, 200);
        } catch (Exception $e) {
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }
}
