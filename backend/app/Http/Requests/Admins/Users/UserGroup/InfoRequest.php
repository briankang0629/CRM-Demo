<?php

namespace App\Http\Requests\Admins\Users\UserGroup;

use App\Http\Requests\Request;

class InfoRequest extends Request
{
    public function authorize()
    {
        return permission('user.userGroup', 'V');
    }

    public function rules()
    {
        return [];
    }
}
