<?php

namespace App\Transformers\Admins\Users;

use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($permission)
    {
        return [
            "permission_id" => $permission->permission_id,
            "name" => $permission->name,
            "permission" => $permission->permission,
            "count" => $permission->admin->count(),
            "status" => $permission->status,
            "created_at" => $permission->created_at,
            "updated_at" => $permission->updated_at,
        ];
    }
}
