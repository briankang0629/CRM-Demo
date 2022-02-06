<?php

namespace App\Http\Requests\Admins\Users\UserGroup;

use App\Http\Requests\Request;
use App\Models\Users\User;

class StoreRequest extends Request
{
    public function authorize()
    {
        return app('permission')->validate('user.userGroup', 'V');
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
            'description' => 'required|string',
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
            'description' => trans('validation.attributes.description'),
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
            'description.required' => trans('validation.required'),
            'name.string' => trans('validation.string'),
            'description.string' => trans('validation.string'),
        ];
    }
}
