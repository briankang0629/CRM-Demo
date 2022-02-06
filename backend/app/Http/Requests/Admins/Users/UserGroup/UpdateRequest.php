<?php

namespace App\Http\Requests\Admins\Users\UserGroup;

use App\Http\Requests\Request;
use App\Models\Users\User;

class UpdateRequest extends Request
{
    public function authorize()
    {
        return permission('user.userGroup', 'E');
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
            'description' => 'string',
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
            'name.string' => trans('validation.string'),
            'description.string' => trans('validation.string'),
        ];
    }
}
