<?php

namespace App\Http\Requests\Admins\Reports;

use App\Http\Requests\Request;

class ProductViewRequest extends Request
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
        ];
    }
}
