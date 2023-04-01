<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function createComment(array $commentDetails)
    {
        return Comment::create($commentDetails);
    }

    public function updateComment(int $id, array $newDetails)
    {
        $comment = $this->getById($id);

        return tap($comment)->update($newDetails);
    }
}
