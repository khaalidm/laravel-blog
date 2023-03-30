<?php

namespace Tests\Feature\Category;

use App\Models\Role;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testRolesAreSeeded(): void
    {
        self::assertTrue(Role::where('name', Role::SUPER)->first() instanceof Role);
        self::assertTrue(Role::where('name', Role::ADMIN)->first() instanceof Role);
        self::assertTrue(Role::where('name', Role::AUTHOR)->first() instanceof Role);
        self::assertTrue(Role::where('name', Role::BASIC_USER)->first() instanceof Role);
    }

}
