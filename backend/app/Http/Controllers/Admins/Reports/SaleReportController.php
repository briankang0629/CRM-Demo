<?php

namespace App\Http\Controllers\Admins\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Reports\SaleHotRequest;
use App\Http\Requests\Admins\Reports\SaleReportRequest;
use App\Services\Reports\ReportService;
use App\Transformers\Admins\Reports\SaleHotTransformer;

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
     * getSaleReport 銷售報表
     *
     * @since 0.0.1
     * @version 0.0.1
     * @return string
     */
    public function getSaleReport(SaleReportRequest $request)
    {
        //取訂單資料
        $orderData = $this->service->getSaleReport($request->toArray());
        return response(['data' => $orderData]);
    }

    /**
     * getSaleHot 取熱銷商品
     *
     * @param SaleHotRequest $request
     * @return \Spatie\Fractal\Fractal
     */
    public function getSaleHot(SaleHotRequest $request)
    {
        //取訂單資料
        $orderData = $this->service->getSaleHot($request->toArray());
        return fractal($orderData, SaleHotTransformer::class);
    }
}
