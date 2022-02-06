<?php

namespace App\Http\Requests\Admins\Users\Admin;

use App\Http\Requests\Request;

class ListRequest extends Request
{
    public function authorize()
    {
        return permission('admin.adminList', 'V');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'string',
            'name' => 'string',
            'email' => 'email',
            'status' => 'in:Y,N',
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
            'name' => trans('validation.attributes.name'),
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
            'account.string' => trans('validation.string'),
            'name.string' => trans('validation.string'),
        ];
    }
}
