<?php

namespace App\Http\Requests\Admins\Users\User;

use App\Http\Requests\Request;
use App\Models\Users\User;

class StoreRequest extends Request
{
    public function authorize()
    {
        return app('permission')->validate('user.userList', 'V');
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
            'account' => 'required|string',
            'email' => 'required|email',
            'password' => 'required',
            'mobile' => 'required|string',
            'status' => 'required|in:' . implode(',', User::STATUSES),
            'user_group_id' => 'required|integer',
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
            'account' => trans('validation.attributes.account'),
            'email' => trans('validation.attributes.email'),
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
            'account.required' => trans('validation.required'),
            'account.string' => trans('validation.string'),
            'password.required' => trans('validation.required'),
            'email.required' => trans('validation.required'),
            'mobile.required' => trans('validation.required'),
            'status.required' => trans('validation.required'),
            'user_group_id.required' => trans('validation.required'),
            'upload_id.required' => trans('validation.required'),
        ];
    }
}
