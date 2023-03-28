<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const ROLE_API       = 'api';
    public const SUPER          = 'super';
    public const ADMIN          = 'admin';
    public const AUTHOR         = 'author';
    public const BASIC_USER     = 'basic-user';

    public const ROLES = [
        self::ROLE_API,
        self::SUPER,
        self::ADMIN,
        self::AUTHOR,
        self::BASIC_USER,
    ];
}
