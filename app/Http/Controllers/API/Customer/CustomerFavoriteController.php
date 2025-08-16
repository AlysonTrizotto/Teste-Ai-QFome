<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\CustomerFavorite;
use App\Services\Customer\CustomerFavoriteService;
use App\Http\Requests\Customer\CustomerFavorite\StoreCustomerFavoriteRequest;
use App\Http\Requests\Customer\CustomerFavorite\UpdateCustomerFavoriteRequest;
use Illuminate\Http\Request;
use App\Http\Resources\Customer\CustomerFavoriteResource;

class CustomerFavoriteController extends Controller
{
    public function __construct(private CustomerFavoriteService $service) {}

    public function index(Request $request)
    {
        $data = $this->service->paginate($request->all(), (int) $request->get('per_page', 15));
        return new CustomerFavoriteResource($data);
    }

    public function store(StoreCustomerFavoriteRequest $request)
    {
        $model = $this->service->create($request->validated());
        return (new CustomerFavoriteResource($model))->response()->setStatusCode(201);
    }

    public function show(CustomerFavorite $customerFavorite)
    {
        return new CustomerFavoriteResource($customerFavorite);
    }

    public function update(CustomerFavorite $customerFavorite, UpdateCustomerFavoriteRequest $request)
    {
        $updated = $this->service->update($customerFavorite, $request->validated());
        return new CustomerFavoriteResource($updated);
    }

    public function destroy(CustomerFavorite $customerFavorite)
    {
        $this->service->delete($customerFavorite);
        return response()->noContent();
    }
}