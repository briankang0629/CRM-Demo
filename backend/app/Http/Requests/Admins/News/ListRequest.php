<?php

namespace App\Http\Requests\Admins\News;

use App\Http\Requests\Request;

class ListRequest extends Request
{
    public function authorize()
    {
        return permission('news.newsList', 'V');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'language' => 'in:' . implode(',', config('system.language')),
            'news_category_id' => 'integer',
            'status' => 'in:Y,N',
            'top' => 'in:Y,N',
            'start_time' => 'date_format:Y-m-d H:i:s',
            'end_time' => 'date_format:Y-m-d H:i:s|after: start_time',
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
            'news_category_id' => trans('validation.attributes.name'),
            'top' => trans('validation.attributes.top'),
            'status' => trans('validation.attributes.status'),
            'start_time' => trans('validation.attributes.start_time'),
            'end_time' => trans('validation.attributes.end_time'),
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
            'news_category_id.integer' => trans('validation.integer'),
            'status.in' => trans('validation.in'),
            'top.in' => trans('validation.in'),
            'start_time.date_format' => trans('validation.date_format'),
            'end_time.date_format' => trans('validation.date_format'),
        ];
    }
}
