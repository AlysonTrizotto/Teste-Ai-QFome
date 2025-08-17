<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure an authenticated user for routes protected by auth:sanctum
        if (!auth('sanctum')->check()) {
            $user = User::factory()->create([
                'password' => Hash::make('password'),
            ]);
            // Authenticate via Sanctum guard for all feature tests
            Sanctum::actingAs($user, ['*'], 'sanctum');
        }
    }
}