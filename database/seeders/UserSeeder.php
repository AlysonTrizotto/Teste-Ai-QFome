<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('SEED_USER_EMAIL', 'admin@example.com')],
            [
                'name' => env('SEED_USER_NAME', 'Admin'),
                'password' => Hash::make(env('SEED_USER_PASSWORD', 'password')),
            ]
        );
    }
}
