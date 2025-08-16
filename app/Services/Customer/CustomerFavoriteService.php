<?php

namespace App\Services\Customer;

use App\Models\Customer\CustomerFavorite;
use App\Services\Products\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Helpers\Concurrency;

class CustomerFavoriteService
{
    public function __construct(private CustomerFavorite $customerFavorite){}

    public function paginate(array $filters = []): LengthAwarePaginator
    {
        
        $data = CustomerFavorite::with('customer:id,name,email')
                                ->select('id', 'customer_id', 'product_id')
                                ->filter($filters)
                                ->paginate();

        $data->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'customer_id' => $item->customer_id,
                'product_id' => $item->product_id,
                'customer' => $item->customer,
                'product' => Concurrency::run([
                    function () use ($item) {
                        $productService = app(ProductService::class);
                        return $productService->showSmallFields($item->product_id);
                    },
                ]),
            ];
        });

        return $data;
    }

    public function show(int $id): CustomerFavorite
    {
        $data = CustomerFavorite::with('customer:id,name,email')->findOrFail($id);
        
        $data->product = app(ProductService::class)->show($data->product_id);

        return $data;
    }

    public function create(array $data): CustomerFavorite
    {
        return CustomerFavorite::create($data);
    }

    public function update(CustomerFavorite $model, array $data): CustomerFavorite
    {
        $model->update($data);
        return $model;
    }

    public function delete(CustomerFavorite $model): void
    {
        $model->delete();
    }

}