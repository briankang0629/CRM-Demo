<?php

namespace App\Http\Requests\Admins\Medias\Image;

use App\Http\Requests\Request;

class ListRequest extends Request
{
    public function authorize()
    {
        return permission('media.image', 'V');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'folder' => 'string',
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
            'folder' => trans('validation.attributes.folder'),
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
            'folder.string' => trans('validation.string'),
            'folder.required' => trans('validation.required'),
        ];
    }
}
