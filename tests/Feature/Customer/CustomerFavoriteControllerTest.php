<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Models\Customer\CustomerFavorite;

class CustomerFavoriteControllerTest extends TestCase
{

    public function test_index_returns_ok(): void
    {
        CustomerFavorite::factory()->count(2)->create();
        $res = $this->getJson('/api/v1/customer-favorites');
        $res->assertStatus(200);
    }

    public function test_store_validates_payload(): void
    {
        $res = $this->postJson('/api/v1/customer-favorites', []);
        $res->assertStatus(422);
    }

    public function test_store_creates_record(): void
    {
        $payload = CustomerFavorite::factory()->make()->toArray();
        $res = $this->postJson('/api/v1/customer-favorites', $payload);
        $res->assertCreated();
        $expected = collect($payload)
            ->except(['created_at','updated_at','deleted_at'])
            ->map(function ($v) {
                if (is_bool($v)) return $v ? 1 : 0;
                if (is_array($v)) return json_encode($v);
                return $v;
            })->toArray();
        $this->assertDatabaseHas('customer_favorites', $expected);
    }

    public function test_show_returns_one(): void
    {
        $model = CustomerFavorite::factory()->create();
        $res = $this->getJson('/api/v1/customer-favorites/' . $model->getKey());
        $res->assertOk();
    }

    public function test_update_updates_record(): void
    {
        $model = CustomerFavorite::factory()->create();
        $changes = CustomerFavorite::factory()->make()->toArray();
        $res = $this->putJson('/api/v1/customer-favorites/' . $model->getKey(), $changes);
        $res->assertOk();
        $expected = collect($changes)
            ->except(['created_at','updated_at','deleted_at'])
            ->map(function ($v) {
                if (is_bool($v)) return $v ? 1 : 0;
                if (is_array($v)) return json_encode($v);
                return $v;
            })->toArray();
        $this->assertDatabaseHas('customer_favorites', $expected);
    }

    public function test_destroy_deletes_record(): void
    {
        $model = CustomerFavorite::factory()->create();
        $res = $this->deleteJson('/api/v1/customer-favorites/' . $model->getKey());
        $res->assertNoContent();
        $this->assertSoftDeleted('customer_favorites', ['id' => $model->getKey()]);
    }
}