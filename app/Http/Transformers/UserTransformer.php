<?php

namespace App\Http\Transformers;

use App\Models\User;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'roles'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'user_id'       => $user->id,
            'first_name'    => $user->first_name,
            'last_name'     => $user->last_name,
            'email'         => $user->email,
            'created_at'    => $user->created_at,
        ];
    }

    public function includeRoles(User $user): Collection
    {
        return $this->collection($user->roles, new RoleTransformer());
    }
}
