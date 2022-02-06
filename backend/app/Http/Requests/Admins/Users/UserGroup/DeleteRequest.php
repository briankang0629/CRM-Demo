<?php

namespace App\Http\Requests\Admins\Users\UserGroup;

use App\Http\Requests\Request;

class DeleteRequest extends Request
{
    public function authorize()
    {
        return permission('user.userGroup', 'E');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}

