<?php

namespace App\Http\Controllers\API\Customer;

use App\Enum\HttpCodeEnum;
use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Services\Customer\CustomerService;
use App\Http\Requests\Customer\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\Customer\UpdateCustomerRequest;
use App\Http\Requests\Customer\Customer\IndexCustomerRequest;
use App\Http\Resources\Customer\CustomerResource;

class CustomerController extends Controller
{
    public function __construct(private CustomerService $service) {}

    public function index(IndexCustomerRequest $request)
    {
        try {
            $data = $this->service->paginate($request->validated());
            return $this->sendSuccessResponse(new CustomerResource($data), 'Customers retrieved successfully', HttpCodeEnum::OK->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }

    public function store(StoreCustomerRequest $request)
    {
        try {
            $model = $this->service->create($request->validated());
            return $this->sendSuccessResponse(new CustomerResource($model), 'Customer created successfully', HttpCodeEnum::CREATED->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }

    public function show(Customer $customer)
    {
        try {
            return $this->sendSuccessResponse(new CustomerResource($customer), 'Customer retrieved successfully', HttpCodeEnum::OK->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }

    public function update(Customer $customer, UpdateCustomerRequest $request)
    {
        try {
            $updated = $this->service->update($customer, $request->validated());
            return $this->sendSuccessResponse(new CustomerResource($updated), 'Customer updated successfully', HttpCodeEnum::OK->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $this->service->delete($customer);
            return $this->sendSuccessResponse(null, 'Customer deleted successfully', HttpCodeEnum::NO_CONTENT->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }
}