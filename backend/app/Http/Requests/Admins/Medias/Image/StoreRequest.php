<?php

namespace App\Http\Requests\Admins\Medias\Image;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function authorize()
    {
        return permission('media.image', 'E');
    }

    protected function prepareForValidation()
    {
        $this->request->add([
            'uploadFile' => $this->file('file')
        ]);

        // header 帶入 folder
        if (!$this->folder && $this->header('folder')) {
            $this->request->add([
                'folder' => $this->header('folder'),
            ]);
        }
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
