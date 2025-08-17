<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Models\Customer\Customer;

class CustomerControllerTest extends TestCase
{
    public function test_index_returns_ok(): void
    {
        Customer::factory()->count(2)->create();
        $res = $this->getJson('/api/v1/customers');
        $res->assertStatus(200);
    }

    public function test_store_validates_payload(): void
    {
        $res = $this->postJson('/api/v1/customers', []);
        $res->assertStatus(422);
    }

    public function test_store_creates_record(): void
    {
        $payload = Customer::factory()->make()->toArray();
        $res = $this->postJson('/api/v1/customers', $payload);
        $res->assertCreated();
        $expected = collect($payload)
            ->except(['created_at','updated_at','deleted_at'])
            ->map(function ($v) {
                if (is_bool($v)) return $v ? 1 : 0;
                if (is_array($v)) return json_encode($v);
                return $v;
            })->toArray();
        $this->assertDatabaseHas('customers', $expected);
    }

    public function test_show_returns_one(): void
    {
        $model = Customer::factory()->create();
        $res = $this->getJson('/api/v1/customers/' . $model->getKey());
        $res->assertOk();
    }

    public function test_update_updates_record(): void
    {
        $model = Customer::factory()->create();
        $changes = Customer::factory()->make()->toArray();
        $res = $this->putJson('/api/v1/customers/' . $model->getKey(), $changes);
        $res->assertOk();
        $expected = collect($changes)
            ->except(['created_at','updated_at','deleted_at'])
            ->map(function ($v) {
                if (is_bool($v)) return $v ? 1 : 0;
                if (is_array($v)) return json_encode($v);
                return $v;
            })->toArray();
        $this->assertDatabaseHas('customers', $expected);
    }

    public function test_destroy_deletes_record(): void
    {
        $model = Customer::factory()->create();
        $res = $this->deleteJson('/api/v1/customers/' . $model->getKey());
        $res->assertNoContent();
        $this->assertSoftDeleted('customers', ['id' => $model->getKey()]);
    }
}