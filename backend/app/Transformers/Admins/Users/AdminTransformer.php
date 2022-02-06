<?php

namespace App\Transformers\Admins\Users;

use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract
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
    public function transform($admin)
    {
        return [
            "admin_id" => $admin->admin_id,
            "parent_id" => $admin->parent_id,
            "name" => $admin->name,
            "account" => $admin->account,
            "email" => $admin->email,
            "status" => $admin->status,
            "picture" => $admin->picture,
            "level" => $admin->level,
            "family" => $admin->family,
            "is_sub" => $admin->is_sub,
            "ip" => $admin->ip,
            "permission" => $admin->permission->name,
            "last_login_time" => $admin->last_login_time,
            "created_at" => $admin->created_at,
            "updated_at" => $admin->updated_at,
        ];
    }
}
