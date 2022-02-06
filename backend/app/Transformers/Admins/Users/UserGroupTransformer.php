<?php

namespace App\Transformers\Admins\Users;

use League\Fractal\TransformerAbstract;
use App\Models\Users\UserGroup;

class UserGroupTransformer extends TransformerAbstract
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
    public function transform(UserGroup $userGroup)
    {
        return [
            "user_group_id" => $userGroup->user_group_id,
            "name" => $userGroup->name,
            "description" => $userGroup->description,
            "count" => $userGroup->users->count(),
            "created_at" => $userGroup->created_at,
            "updated_at" => $userGroup->updated_at,
        ];
    }
}
