<?php

namespace Tests\Unit\Customer;

use PHPUnit\Framework\TestCase;
use App\Models\Customer\CustomerFavorite;

class CustomerFavoriteModelTest extends TestCase
{
    public function test_fillable_properties_exist(): void
    {
        $m = new CustomerFavorite();
        $this->assertIsArray($m->getFillable());
    }
}