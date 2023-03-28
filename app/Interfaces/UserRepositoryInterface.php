<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function createUser(array $useretails);

    public function updateUser(array $newDetails);
}
