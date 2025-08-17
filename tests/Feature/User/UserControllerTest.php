<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User\User;
use Illuminate\Support\Str;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_index_returns_ok(): void
    {
        User::factory()->count(2)->create();
        $res = $this->getJson('/api/v1/users');
        $res->assertStatus(200);
    }

    public function test_store_validates_payload(): void
    {
        $res = $this->postJson('/api/v1/users', []);
        $res->assertStatus(422);
    }

    public function test_store_creates_record(): void
    {
        $payload = User::factory()->make()->toArray();
        $res = $this->postJson('/api/v1/users', $payload);
        $res->assertCreated();
        $expected = collect($payload)
            ->except(['created_at','updated_at','deleted_at'])
            ->map(function ($v) {
                if (is_bool($v)) return $v ? 1 : 0;
                if (is_array($v)) return json_encode($v);
                return $v;
            })->toArray();
        $this->assertDatabaseHas('users', $expected);
    }

    public function test_show_returns_one(): void
    {
        $model = User::factory()->create();
        $res = $this->getJson('/api/v1/users/' . $model->getKey());
        $res->assertOk();
    }

    public function test_update_updates_record(): void
    {
        $model = User::factory()->create();
        $changes = User::factory()->make()->toArray();
        $res = $this->putJson('/api/v1/users/' . $model->getKey(), $changes);
        $res->assertOk();
        $expected = collect($changes)
            ->except(['created_at','updated_at','deleted_at'])
            ->map(function ($v) {
                if (is_bool($v)) return $v ? 1 : 0;
                if (is_array($v)) return json_encode($v);
                return $v;
            })->toArray();
        $this->assertDatabaseHas('users', $expected);
    }

    public function test_destroy_deletes_record(): void
    {
        $model = User::factory()->create();
        $res = $this->deleteJson('/api/v1/users/' . $model->getKey());
        $res->assertNoContent();
        $this->assertSoftDeleted('users', ['id' => $model->getKey()]);
    }
}