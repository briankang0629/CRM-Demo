<?php

namespace App\Http\Requests\Admins\Products\Category;

use App\Http\Requests\Request;

class InfoRequest extends Request
{
    public function authorize()
    {
        return permission('product.productCategory', 'V');
    }
}
