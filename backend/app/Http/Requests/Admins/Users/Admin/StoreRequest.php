<?php

namespace App\Http\Requests\Admins\Users\Admin;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function authorize()
    {
        return app('permission')->validate('admin.adminList', 'V');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'required|string',
            'password' => 'required'
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
            'account' => trans('validation.attributes.account'),
            'password' => trans('validation.attributes.password'),
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
            'account.required' => trans('validation.required'),
            'account.string' => trans('validation.string'),
            'password.required' => trans('validation.required'),
        ];
    }
}
