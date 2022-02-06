<?php

namespace App\Services\Orders;

use App\Models\Orders\Order;
use Carbon\Carbon;

class OrderService
{
    const CHUNK = 200;

    /**
     * @var object
     */
    private $order;

    /**
     * OrderService constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function getOrderGroupByMonth()
    {
        for ($int = 1; $int <= 12; $int++) {
            $data[] = array(
                'month' => date('Y-m', mktime(0, 0, 0, $int)),
                'total' => 0
            );
        }

        // 單次最多取200筆
        $orders = $this->order->getOrderGroupByMonth();

        // 取月份訂單
        foreach ($orders as $key => $order) {
            $data[$key] = [
                'month' => date('Y-m', strtotime($order->created_at)),
                'total' => (int)$order->total
            ];
        }

        return $data;
    }
}
