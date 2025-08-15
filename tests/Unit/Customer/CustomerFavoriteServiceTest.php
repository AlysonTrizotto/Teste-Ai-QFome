<?php

namespace Tests\Unit\Customer;

use PHPUnit\Framework\TestCase;
use App\Services\Customer\CustomerFavoriteService;
use App\Models\Customer\CustomerFavorite;

class CustomerFavoriteServiceTest extends TestCase
{
    public function test_can_instantiate_service(): void
    {
        $s = new CustomerFavoriteService();
        $this->assertInstanceOf(CustomerFavoriteService::class, $s);
    }
}