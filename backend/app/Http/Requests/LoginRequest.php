<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class LoginRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'required|string',
            'password' => 'required',
        ];
    }

    /**
     * attributes
     *
     * @return array
     */
    public function attributes () {
        return [
            'account' => '帳號',
            'password' => '密碼',
        ];
    }
}
