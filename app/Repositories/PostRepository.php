<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function createPost(array $postDetails)
    {
        return Post::create($postDetails);
    }

    public function updatePost(int $id, array $newDetails)
    {
        $post = $this->getById($id);

        return tap($post)->update($newDetails);
    }

    public function toggleActiveState(int $id)
    {
        $post = $this->getById($id);
        $post->active = !$post->active;
        return $post->save();
    }
}
