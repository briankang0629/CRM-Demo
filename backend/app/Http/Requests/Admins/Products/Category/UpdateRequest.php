<?php

namespace App\Http\Requests\Admins\Products\Category;

use App\Rules\ProductCategoryPath;
use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function authorize()
    {
        return permission('product.productCategory', 'E');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => [
                'required',
                'integer',
                new ProductCategoryPath($this->id) //不得指定 parentId 為當前下線子分類
            ],
            'status' => 'in:Y,N',
            'sort_order' => 'integer',
            'details' => 'required|array',
            'details.*.language' => 'required|in:' . implode(',', config('system.language')),
            'details.*.description' => 'required|string',
            'details.*.meta_title' => 'required|string',
            'details.*.meta_description' => 'required|string',
            'details.*.meta_keyword' => 'required|string',
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
            'parent_id' => trans('validation.attributes.parent_id'),
            'status' => trans('validation.attributes.status'),
            'details' => trans('validation.attributes.details'),
            'details.*.language' => trans('validation.attributes.language'),
            'details.*.description' => trans('validation.attributes.description'),
            'details.*.meta_title' => trans('validation.attributes.meta_title'),
            'details.*.meta_description' => trans('validation.attributes.meta_description'),
            'details.*.meta_keyword' => trans('validation.attributes.meta_keyword'),
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
            'parent_id.integer' => trans('validation.integer'),
            'status.in' => trans('validation.in'),
            'details.array' => trans('validation.array'),
            'details.*.language.in' => trans('validation.in'),
            'details.*.description.string' => trans('validation.string'),
            'details.*.meta_title.string' => trans('validation.string'),
            'details.*.meta_description.string' => trans('validation.string'),
            'details.*.meta_keyword.string' => trans('validation.string'),
        ];
    }
}
