<?php

namespace Tests\Unit\User;

use PHPUnit\Framework\TestCase;
use App\Services\User\UserService;
use App\Models\User\User;

class UserServiceTest extends TestCase
{
    public function test_can_instantiate_service(): void
    {
        $s = new UserService();
        $this->assertInstanceOf(UserService::class, $s);
    }
}