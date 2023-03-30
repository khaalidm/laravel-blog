<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function createUser(array $userDetails)
    {
        return User::create($userDetails);
    }

    public function updateUser(int $id, array $newDetails)
    {
        $user = $this->getById($id);

        return tap($user)->update($newDetails);
    }
}
