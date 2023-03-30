<?php

namespace App\Http\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
//        'posts'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'category_id'   => $category->id,
            'name'          => $category->name,
            'description'   => $category->description,
            'created_at'    => $category->created_at,
        ];
    }

    // TODO Come back and complete, a Category can have multiple posts
//    public function includePosts(User $user): Collection
//    {
//        return $this->collection($user->roles, new RoleTransformer());
//    }
}
