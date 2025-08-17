<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations; 

    protected function setUp(): void
    {
        parent::setUp();

        // autentica para as rotas usadas no teste
        if (!auth('sanctum')->check()) {
            $user = User::factory()->create([
                'password' => Hash::make('password'),
            ]);

            Sanctum::actingAs($user, ['*'], 'sanctum');
        }
    }
}