<?php

namespace App\Http\Requests\Admins\News;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function authorize()
    {
        return permission('news.newsList', 'E');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sort_order' => 'required|int',
            'top' => 'required|in:Y,N',
            'status' => 'required|in:Y,N',
            'categories' => 'required',
            'upload_id' => 'int',
            'available_time' => 'date_format:Y-m-d H:i:s',

            //details
            'details' => 'required|array',
            'details.*.language' => 'required|in:' . implode(',', config('system.language')),
            'details.*.intro' => 'string',
            'details.*.description' => 'required|string',
            'details.*.meta_title' => 'string',
            'details.*.meta_description' => 'string',
            'details.*.meta_keyword' => 'string',
            'details.*.tag' => 'string',
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
            'top' => trans('validation.attributes.top'),
            'available_time' => trans('validation.attributes.available_time'),
            'upload_id' => trans('validation.attributes.upload_id'),
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
