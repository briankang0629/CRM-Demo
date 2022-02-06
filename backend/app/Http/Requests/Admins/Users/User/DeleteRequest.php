<?php

namespace App\Http\Requests\Admins\Users\User;

use App\Http\Requests\Request;

class DeleteRequest extends Request
{
    public function authorize()
    {
        return permission('user.userList', 'E');
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

