<?php

namespace App\Services\Customer;

use App\Models\Customer\CustomerFavorite;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerFavoriteService
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        return CustomerFavorite::query()->filter($filters)->paginate();
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