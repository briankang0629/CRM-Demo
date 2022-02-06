<?php

namespace App\Http\Controllers\Admins\Reports;

use App\Http\Controllers\Controller;
use App\Services\Orders\OrderService;

class OrderReportController extends Controller
{
    private $orderService;

    /**
     * OrderReportController constructor.
     *
     * @param OrderService $service
     */
    public function __construct(OrderService $service)
    {
        $this->orderService = $service;
    }

    /**
     * getOrderTotal 訂單數量
     *
     * @return string
     */
    public function getOrderTotal()
    {
        return (double)\App\Models\Orders\Order::count();
    }

    /**
     * getOrderSale 訂單銷售總額
     *
     * @return string
     */
    public function getOrderSale()
    {
        return (double)\App\Models\Orders\OrderTotal::sum('value');
    }

    /**
     * getOrderGroupByMonth 月份組合訂單數
     * @return array
     */
    public function getOrderGroupByMonth()
    {
        return $this->orderService->getOrderGroupByMonth();
    }
}
