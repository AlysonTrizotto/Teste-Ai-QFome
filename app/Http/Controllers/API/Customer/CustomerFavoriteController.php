<?php

namespace App\Http\Controllers\API\Customer;

use App\Enum\HttpCodeEnum;
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
        try {
            $data = $this->service->paginate($request->validated());
            return $this->sendSuccessResponse(new CustomerFavoriteResource($data), 'Customer favorites retrieved successfully', HttpCodeEnum::OK->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }

    public function store(StoreCustomerFavoriteRequest $request)
    {
        try {
            $model = $this->service->create($request->validated());
            return $this->sendSuccessResponse(new CustomerFavoriteResource($model), 'Customer favorite created successfully', HttpCodeEnum::CREATED->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }

    public function show(int $id)
    {
        try {
            return $this->sendSuccessResponse(new CustomerFavoriteResource($this->service->show($id)), 'Customer favorite retrieved successfully', HttpCodeEnum::OK->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }

    public function update(CustomerFavorite $customerFavorite, UpdateCustomerFavoriteRequest $request)
    {
        try {
            $updated = $this->service->update($customerFavorite, $request->validated());
            return $this->sendSuccessResponse(new CustomerFavoriteResource($updated), 'Customer favorite updated successfully', HttpCodeEnum::OK->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }

    public function destroy(CustomerFavorite $customerFavorite)
    {
        try {
            $this->service->delete($customerFavorite);
            return $this->sendSuccessResponse(null, 'Customer favorite deleted successfully', HttpCodeEnum::NO_CONTENT->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }
}