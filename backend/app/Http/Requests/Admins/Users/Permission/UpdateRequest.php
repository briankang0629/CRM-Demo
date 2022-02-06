<?php

namespace App\Http\Requests\Admins\Users\Permission;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function authorize()
    {
        return permission('admin.permission', 'E');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string',
            'permission' => 'array',
            'status' => 'in:Y,N'
        ];
    }

    /**
     * attributes
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => trans('validation.attributes.name'),
            'permission' => trans('validation.attributes.permission'),
            'status' => trans('validation.attributes.status'),
        ];
    }

    /**
     * messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.string' => trans('validation.string'),
            'permission.array' => trans('validation.array'),
            'status.in' => trans('validation.in'),
        ];
    }
}
