<?php

namespace Database\Factories\Customer;

use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = \App\Models\Customer\Customer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->text(50),
            'email' => fake()->word(),
            'password' => fake()->text(50)
        ];
    }
}