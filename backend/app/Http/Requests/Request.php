<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function getParams()
    {
        $fields = array_keys($this->rules());
        $fields = array_map(function ($field) {
            return strstr($field, '.*', true) ?: $field;
        }, $fields);
        return $this->all($fields);
    }

    public function rules()
    {
        return [];
    }

}
