<?php

namespace App\Http\Requests\Admins\News\Category;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function authorize()
    {
        return permission('news.newsCategory', 'E');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'in:Y,N',
            'sort_order' => 'integer',
            'details' => 'required|array',
            'details.*.language' => 'required|in:' . implode(',', config('system.language')),
            'details.*.name' => 'required|string',
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
            'status' => trans('validation.attributes.status'),
            'detail' => trans('validation.attributes.detail'),
            'details.*.language' => trans('validation.attributes.language'),
            'details.*.name' => trans('validation.attributes.name'),
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
            'status.in' => trans('validation.in'),
            'details.array' => trans('validation.array'),
            'details.*.language.in' => trans('validation.in'),
            'details.*.name.string' => trans('validation.string'),
            'details.*.description.string' => trans('validation.string'),
            'details.*.meta_title.string' => trans('validation.string'),
            'details.*.meta_description.string' => trans('validation.string'),
            'details.*.meta_keyword.string' => trans('validation.string'),
        ];
    }
}
