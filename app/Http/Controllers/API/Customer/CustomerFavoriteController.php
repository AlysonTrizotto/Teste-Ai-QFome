<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\CustomerFavorite;
use App\Services\Customer\CustomerFavoriteService;
use App\Http\Requests\Customer\CustomerFavorite\StoreCustomerFavoriteRequest;
use App\Http\Requests\Customer\CustomerFavorite\UpdateCustomerFavoriteRequest;
use App\Http\Requests\Customer\CustomerFavorite\IndexCustomerFavoriteRequest;
use App\Http\Resources\Customer\CustomerFavoriteResource;

class CustomerFavoriteController extends Controller
{
    public function __construct(private CustomerFavoriteService $service) {}

    public function index(IndexCustomerFavoriteRequest $request)
    {
        $data = $this->service->paginate($request->validated());
        return new CustomerFavoriteResource($data);
    }

    public function store(StoreCustomerFavoriteRequest $request)
    {
        $model = $this->service->create($request->validated());
        return (new CustomerFavoriteResource($model))->response()->setStatusCode(201);
    }

    public function show(int $id)
    {
        return new CustomerFavoriteResource($this->service->show($id));
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