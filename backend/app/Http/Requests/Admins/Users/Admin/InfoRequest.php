<?php

namespace App\Http\Requests\Admins\Users\Admin;

use App\Http\Requests\Request;

class InfoRequest extends Request
{
    public function authorize()
    {
        return permission('admin.adminList', 'V');
    }

    public function rules()
    {
        return [];
    }
}
