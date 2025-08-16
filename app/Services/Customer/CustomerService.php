<?php

namespace App\Services\Customer;

use App\Models\Customer\Customer;

class CustomerService
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        return Customer::query()->filter($filters)->paginate($perPage);
    }

    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function update(Customer $model, array $data): Customer
    {
        $model->update($data);
        return $model;
    }

    public function delete(Customer $model): void
    {
        $model->delete();
    }
}