<?php

namespace App\Http\Requests\Users\Products;

use App\Http\Requests\Request;

class ListRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'language' => 'in:' . implode(',', config('system.language')),
            'name' => 'string',
            'parent_id' => 'integer',
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
            'language' => trans('validation.attributes.language'),
            'name' => trans('validation.attributes.name'),
            'parent_id' => trans('validation.attributes.parent_id'),
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
            'language.string' => trans('validation.string'),
            'name.string' => trans('validation.string'),
            'parent_id.integer' => trans('validation.integer'),
            'status.in' => trans('validation.in'),
        ];
    }
}
