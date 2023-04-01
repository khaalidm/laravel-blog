<?php

namespace App\Http\Transformers;

use App\Models\Post;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;


class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'comments'
    ];

    public function transform(Post $post)
    {
        return [
            'category_id'   => $post->category_id,
            'user_id'       => $post->user_id,
            'title'         => $post->title,
            'description'   => $post->description,
            'body'          => $post->body,
            'created_at'    => $post->created_at
        ];
    }

    public function includeComments(Post $user): Collection
    {
        return $this->collection($user->comments, new CommentTransformer());
    }
}
