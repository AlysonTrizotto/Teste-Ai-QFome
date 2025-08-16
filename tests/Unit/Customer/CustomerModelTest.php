<?php

namespace Tests\Unit\Customer;

use PHPUnit\Framework\TestCase;
use App\Models\Customer\Customer;

class CustomerModelTest extends TestCase
{
    public function test_fillable_properties_exist(): void
    {
        $m = new Customer();
        $this->assertIsArray($m->getFillable());
    }
}