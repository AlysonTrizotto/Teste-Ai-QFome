<?php

namespace Tests\Unit\Customer;

use PHPUnit\Framework\TestCase;
use App\Services\Customer\CustomerService;
use App\Models\Customer\Customer;

class CustomerServiceTest extends TestCase
{
    public function test_can_instantiate_service(): void
    {
        $s = new CustomerService();
        $this->assertInstanceOf(CustomerService::class, $s);
    }
}