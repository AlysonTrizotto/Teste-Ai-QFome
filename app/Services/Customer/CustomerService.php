<?php

namespace App\Services\Customer;

use App\Models\Customer\Customer;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerService
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        return Customer::query()->filter($filters)->paginate();
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