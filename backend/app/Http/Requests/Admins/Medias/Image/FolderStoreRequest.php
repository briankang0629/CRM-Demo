<?php

namespace App\Http\Requests\Admins\Medias\Image;

use App\Http\Requests\Request;

class FolderStoreRequest extends Request
{
    public function authorize()
    {
        return permission('media.image', 'E');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string',
            'name' => 'required|string',
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
            'code' => trans('validation.attributes.code'),
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
            'code.string' => trans('validation.string'),
            'code.required' => trans('validation.required'),
            'name.string' => trans('validation.string'),
            'name.required' => trans('validation.required'),
        ];
    }
}
