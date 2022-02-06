<?php

namespace App\Http\Requests\Admins\Users\Admin;

use App\Http\Requests\Request;

class DeleteRequest extends Request
{
    public function authorize()
    {
        return permission('admin.adminList', 'E');
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

