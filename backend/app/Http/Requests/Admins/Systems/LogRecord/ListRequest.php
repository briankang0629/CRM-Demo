<?php

namespace App\Http\Requests\Admins\Systems\LogRecord;

use App\Http\Requests\Request;

class ListRequest extends Request
{
    public function authorize()
    {
        return permission('logRecord.logRecordList', 'V');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'string',
            'log_id' => 'integer',
            'class' => 'in:A,U,S',
            'start_date' => 'date_format:Y-m-d',
            'end_date' => 'date_format:Y-m-d|after:start_date',
            'remote_ip' => 'ip',
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
            'account' => trans('validation.attributes.account'),
            'log_id' => trans('validation.attributes.log_id'),
            'class' => trans('validation.attributes.class'),
            'start_date' => trans('validation.attributes.start_date'),
            'end_date' => trans('validation.attributes.end_date'),
            'remote_ip' => trans('validation.attributes.remote_ip'),
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
            'account.string' => trans('validation.string'),
            'log_id.integer' => trans('validation.integer'),
            'class.in' => trans('validation.in'),
            'remote_ip.ip' => trans('validation.ip'),
            'start_date.datetime' => trans('validation.date_format'),
            'end_date.datetime' => trans('validation.date_format'),
        ];
    }
}
