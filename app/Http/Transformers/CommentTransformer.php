<?php

namespace App\Http\Transformers;


use App\Models\Comment;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;


class CommentTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'user'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Comment $comment)
    {
        return [
            'user_id'       => $comment->user_id,
            'post_id'       => $comment->post_id,
            'text'          => $comment->text,
            'created_at'    => $comment->created_at,
        ];
    }

    public function includeComments(Comment $comment): Collection
    {
        return $this->collection($comment->user, new UserTransformer());
    }
}
