<?php

namespace Tests\Feature\User\Authenticable;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;

class AuthenticateControllerTest extends TestCase
{
    public function test_authenticate_success_returns_token_and_user(): void
    {
        $password = 'secret123';
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make($password),
        ]);

        $res = $this->postJson('/api/v1/users/authenticate', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $res->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => ['id', 'name', 'email']
                ]
            ]);
    }

    public function test_authenticate_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        $res = $this->postJson('/api/v1/users/authenticate', [
            'email' => 'login@example.com',
            'password' => 'wrong-password',
        ]);

        $res->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'message',
                'errors'
            ]);
    }
}

