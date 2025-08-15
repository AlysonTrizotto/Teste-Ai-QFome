<?php

namespace App\Services\Customer;

use App\Models\Customer\CustomerFavorite;

class CustomerFavoriteService
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        return CustomerFavorite::query()->filter($filters)->paginate($perPage);
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