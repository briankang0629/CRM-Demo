<?php

namespace App\Http\Requests\Admins\Products;

use App\Http\Requests\Request;
use App\Models\Products\ProductOption;

class UpdateRequest extends Request
{
    public function authorize()
    {
        return permission('product.productList', 'E');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'model' => 'required|string',
            'cost_price' => 'required|numeric',
            'price' => 'required|numeric',
            'sort_order' => 'required|int',
            'status' => 'required|in:Y,N',
            'categories' => 'required',
            'upload_id' => 'int',

            //details
            'details' => 'required|array',
            'details.*.language' => 'required|in:' . implode(',', config('system.language')),
            'details.*.intro' => 'string',
            'details.*.description' => 'required|string',
            'details.*.meta_title' => 'string',
            'details.*.meta_description' => 'string',
            'details.*.meta_keyword' => 'string',
//            'details.*.tag' => 'string',

            //options
            'options' => 'array',
            'options.*.product_option_ud' => 'int',
            'options.*.type' => 'required|in:' . implode(',', ProductOption::TYPES),
            'options.*.required' => 'required|in:Y,N',
            'options.*.sort_order' => 'required|numeric',

            //options details
            'options.*.details' => 'required|array',
            'options.*.details.*.language' => 'required|in:' . implode(',', config('system.language')),
            'options.*.details.*.name' => 'required|string',

            //options values
            'options.*.values' => 'array',
            'options.*.values.*.product_option_values_id' => 'int',
            'options.*.values.*.price' => 'required|numeric',
//            'options.*.values.*.sort_order' => 'required|numeric',
            'options.*.values.*.quantity' => 'required|numeric',
            'options.*.values.*.point' => 'required|numeric',
            'options.*.values.*.weight' => 'required|numeric',
//            'options.*.values.*.is_stock' => 'required|in:Y,N',

            //options values details
            'options.*.values.*.details' => 'required|array',
            'options.*.values.*.details.*.language' => 'required|in:' . implode(',', config('system.language')),
            'options.*.values.*.details.*.name' => 'required|string',
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
            'cost_price' => trans('validation.attributes.cost_price'),
            'price' => trans('validation.attributes.price'),
            'sort_order' => trans('validation.attributes.sort_order'),
            'status' => trans('validation.attributes.status'),
            'categories' => trans('validation.attributes.categories'),

            //details
            'details.*.language' => trans('validation.attributes.language'),
            'details.*.intro' => trans('validation.attributes.intro'),
            'details.*.description' => trans('validation.attributes.description'),
            'details.*.meta_title' => trans('validation.attributes.meta_title'),
            'details.*.meta_description' => trans('validation.attributes.meta_description'),
            'details.*.meta_keyword' => trans('validation.attributes.meta_keyword'),
            'details.*.tag' => trans('validation.attributes.tag'),

            //options
            'options' => trans('validation.attributes.options'),
            'options.*.type' => trans('validation.attributes.type'),
            'options.*.required' => trans('validation.attributes.required'),
            'options.*.sort_order' => trans('validation.attributes.sort_order'),

            //options details
            'options.*.details' => trans('validation.attributes.details'),
            'options.*.details.*.language' => trans('validation.attributes.language'),
            'options.*.details.*.name' => trans('validation.attributes.name'),

            //options values
            'options.*.values' => trans('validation.attributes.values'),
            'options.*.values.*.price' => trans('validation.attributes.price'),
            'options.*.values.*.sort_order' => trans('validation.attributes.sort_order'),
            'options.*.values.*.quantity' => trans('validation.attributes.quantity'),
            'options.*.values.*.point' => trans('validation.attributes.point'),
            'options.*.values.*.weight' => trans('validation.attributes.weight'),

            //options values details
            'options.*.values.*.details' => trans('validation.attributes.details'),
            'options.*.values.*.details.*.language' => trans('validation.attributes.language'),
            'options.*.values.*.details.*.name' => trans('validation.attributes.name'),
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
