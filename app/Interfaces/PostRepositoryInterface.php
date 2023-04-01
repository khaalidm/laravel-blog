<?php

namespace App\Interfaces;

interface PostRepositoryInterface
{
    public function createPost(array $postDetails);

    public function updatePost(int $id, array $newDetails);

    public function toggleActiveState(int $id);
}
