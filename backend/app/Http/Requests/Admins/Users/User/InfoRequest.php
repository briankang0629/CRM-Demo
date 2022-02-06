<?php

namespace App\Http\Requests\Admins\Users\User;

use App\Http\Requests\Request;

class InfoRequest extends Request
{
    public function authorize()
    {
        return permission('user.userList', 'V');
    }

    public function rules()
    {
        return [];
    }
}
