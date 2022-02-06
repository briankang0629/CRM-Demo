<?php

namespace App\Http\Controllers\Users\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Reports\SaleHotRequest;
use App\Services\Reports\ReportService;
use App\Transformers\Users\Reports\SaleHotTransformer;
use Illuminate\Http\Request;

class SaleReportController extends Controller
{
    private $service;

    /**
     * SaleReportController constructor.
     *
     * @param ReportService $service
     */
    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    /**
     * @todo module
     * getSaleHot 取熱銷商品
     *
     * @param SaleHotRequest $request
     * @return \Spatie\Fractal\Fractal
     */
    public function getSaleHot(Request $request)
    {
        //取訂單資料
        $orderData = $this->service->getSaleHot($request->toArray());
        return fractal($orderData, SaleHotTransformer::class);
    }
}
