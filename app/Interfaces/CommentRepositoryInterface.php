<?php

namespace App\Interfaces;

interface CommentRepositoryInterface
{
    public function createComment(array $commentDetails);

    public function updateComment(int $id, array $newDetails);
}
