<?php

namespace Tests\Unit\Customer;

use PHPUnit\Framework\TestCase;
use App\Services\Customer\CustomerFavoriteService;

class CustomerFavoriteServiceTest extends TestCase
{
    public function test_can_instantiate_service(): void
    {
        $s = new CustomerFavoriteService();
        $this->assertInstanceOf(CustomerFavoriteService::class, $s);
    }
}