<?php

namespace Tests\Unit\User;

use PHPUnit\Framework\TestCase;
use App\Models\User\User;

class UserModelTest extends TestCase
{
    public function test_fillable_properties_exist(): void
    {
        $m = new User();
        $this->assertIsArray($m->getFillable());
    }
}