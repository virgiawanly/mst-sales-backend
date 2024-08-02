<?php

namespace App\Http\Controllers\WebApp\Sales;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\BaseResourceController;
use App\Http\Requests\WebApp\Sales\CreateSalesRequest;
use App\Http\Requests\WebApp\Sales\UpdateSalesRequest;
use App\Services\SalesService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends BaseResourceController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SalesService $salesService)
    {
        parent::__construct($salesService->repository);
    }

    /**
     * Get sales code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSalesCode(Request $request)
    {
        try {
            $result = $this->salesService->getSalesCode($request->sales_id ?? null);
            return ResponseHelper::success('Successfully retrieved sales code.', $result, 200);
        } catch (Exception $e) {
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WebApp\Sales\CreateSalesRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateSalesRequest $request)
    {
        try {
            DB::beginTransaction();
            $result = $this->salesService->save($request->validated());
            DB::commit();
            return ResponseHelper::success('Successfully created.', $result, 201);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return ResponseHelper::notFound($e->getMessage(), $e);
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $result = $this->salesService->find($id);
            $result->load(['customer', 'details', 'details.barang']);

            return ResponseHelper::data($result);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::notFound('Resource not found.');
        } catch (Exception $e) {
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\WebApp\Sales\UpdateSalesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSalesRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $result = $this->salesService->update($id, $request->validated());
            DB::commit();
            return ResponseHelper::success('Successfully updated.', $result, 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return ResponseHelper::notFound($e->getMessage(), $e);
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }
}
