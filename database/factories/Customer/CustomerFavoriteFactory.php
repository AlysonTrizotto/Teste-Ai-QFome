<?php

namespace Database\Factories\Customer;

use App\Models\Customer\CustomerFavorite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CustomerFavorite>
 */
class CustomerFavoriteFactory extends Factory
{
    protected $model = \App\Models\Customer\CustomerFavorite::class;

    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer\Customer::factory(),
            'product_id' => fake()->numberBetween(1, 100000)
        ];
    }
}