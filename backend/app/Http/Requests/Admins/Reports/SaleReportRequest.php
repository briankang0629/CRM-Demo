<?php

namespace App\Http\Requests\Admins\Reports;

use App\Http\Requests\Request;

class SaleReportRequest extends Request
{
    public function authorize()
    {
        return permission('report.saleReport', 'V');
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'timeType' => 'required|in:year,month,day,season',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ];
    }
}
