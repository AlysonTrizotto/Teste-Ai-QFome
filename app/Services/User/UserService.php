<?php

namespace App\Services\User;

use App\Models\User\User;

class UserService
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        return User::query()->filter($filters)->paginate($perPage);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $model, array $data): User
    {
        $model->update($data);
        return $model;
    }

    public function delete(User $model): void
    {
        $model->delete();
    }
}