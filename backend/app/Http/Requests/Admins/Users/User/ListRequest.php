<?php

namespace App\Http\Requests\Admins\Users\User;

use App\Http\Requests\Request;

class ListRequest extends Request
{
    public function authorize()
    {
        return permission('user.userList', 'V');
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
            'user_group_id' => 'integer',
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
            'user_group_id' => trans('validation.attributes.user_group_id'),
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
            'user_group_id.integer' => trans('validation.integer'),
        ];
    }
}
