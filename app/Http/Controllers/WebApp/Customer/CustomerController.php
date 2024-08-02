<?php

namespace App\Http\Controllers\WebApp\Customer;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\BaseResourceController;
use App\Http\Requests\WebApp\Customer\CreateCustomerRequest;
use App\Http\Requests\WebApp\Customer\UpdateCustomerRequest;
use App\Repositories\CustomerRepository;
use Exception;

class CustomerController extends BaseResourceController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CustomerRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WebApp\Customer\CreateCustomerRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCustomerRequest $request)
    {
        try {
            $result = $this->service->save($request->validated());
            return ResponseHelper::success('Successfully created.', $result, 201);
        } catch (Exception $e) {
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\WebApp\Customer\UpdateCustomerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCustomerRequest $request, int $id)
    {
        try {
            $result = $this->service->patch($id, $request->validated());
            return ResponseHelper::success('Successfully updated.', $result, 200);
        } catch (Exception $e) {
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }
}
