<?php

namespace App\Http\Requests\Admins\Users\Permission;

use App\Http\Requests\Request;

class StoreRequest extends Request
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
            'name' => 'required|string',
            'permission' => 'required|array',
            'status' => 'required|in:Y,N'
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
            'name.required' => trans('validation.required'),
            'name.string' => trans('validation.string'),
            'permission.required' => trans('validation.required'),
            'permission.json' => trans('validation.json'),
            'status.required' => trans('validation.required'),
            'status.in' => trans('validation.in'),
        ];
    }
}
