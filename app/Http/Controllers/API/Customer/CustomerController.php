<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Services\Customer\CustomerService;
use App\Http\Requests\Customer\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\Customer\UpdateCustomerRequest;
use App\Http\Requests\Customer\IndexCustomerRequest;
use App\Http\Resources\Customer\CustomerResource;

class CustomerController extends Controller
{
    public function __construct(private CustomerService $service) {}

    public function index(IndexCustomerRequest $request)
    {
        $data = $this->service->paginate($request->validated(), (int) $request->get('per_page', 15));
        return new CustomerResource($data);
    }

    public function store(StoreCustomerRequest $request)
    {
        $model = $this->service->create($request->validated());
        return (new CustomerResource($model))->response()->setStatusCode(201);
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    public function update(Customer $customer, UpdateCustomerRequest $request)
    {
        $updated = $this->service->update($customer, $request->validated());
        return new CustomerResource($updated);
    }

    public function destroy(Customer $customer)
    {
        $this->service->delete($customer);
        return response()->noContent();
    }
}