<?php

namespace App\Transformers\Admins\Users;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
    public function transform($user)
    {
        return [
            "user_id" => $user->user_id,
            "user_group_id" => $user->user_group_id,
            "userGroup" => $user->userGroup,
            "name" => $user->name,
            "account" => $user->account,
            "email" => $user->email,
            "status" => $user->status,
            "mobile" => $user->mobile,
            "ip" => $user->ip,
            "last_login_time" => $user->last_login_time,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at,
        ];
    }
}
