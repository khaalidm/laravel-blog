<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Role;

class RoleTransformer extends TransformerAbstract
{
    /**
     * @param Role $role
     *
     * @return array
     */
    public function transform(Role $role): array
    {
        return [
            'name' => $role->name
        ];
    }
}
