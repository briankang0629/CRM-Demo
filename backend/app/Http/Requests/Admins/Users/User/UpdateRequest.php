<?php

namespace App\Http\Requests\Admins\Users\User;

use App\Http\Requests\Request;
use App\Models\Users\User;

class UpdateRequest extends Request
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
        return [
            'name' => 'string',
            'password' => 'string',
            'mobile' => 'string',
            'status' => 'in:' . implode(',', User::STATUSES),
            'user_group_id' => 'integer',
            'upload_id' => 'integer',
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
            'password' => trans('validation.attributes.password'),
            'mobile' => trans('validation.attributes.mobile'),
            'status' => trans('validation.attributes.status'),
            'user_group_id' => trans('validation.attributes.user_group_id'),
            'upload_id' => trans('validation.attributes.upload_id'),
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
            'password.required' => trans('validation.required'),
        ];
    }
}
