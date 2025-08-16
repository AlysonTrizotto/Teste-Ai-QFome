<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Services\Customer\CustomerService;
use App\Http\Requests\Customer\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\Customer\UpdateCustomerRequest;
use Illuminate\Http\Request;
use App\Http\Resources\Customer\CustomerResource;

class CustomerController extends Controller
{
    public function __construct(private CustomerService $service) {}

    public function index(Request $request)
    {
        $data = $this->service->paginate($request->all(), (int) $request->get('per_page', 15));
        // Wrap paginator in Resource so it handles pagination metadata automatically
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