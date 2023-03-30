<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function createUser(array $userDetails);

    public function updateUser(int $id, array $newDetails);
}
