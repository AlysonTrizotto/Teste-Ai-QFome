<?php

namespace Database\Factories\User;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = \App\Models\User\User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->text(50),
            'email' => fake()->text(50),
            'password' => fake()->text(50)
        ];
    }
}