<?php

namespace App\Services\Reports;

use App\Exceptions\CustomException;
use App\Models\Orders\Order;
use App\Models\Orders\OrderProduct;

class ReportService
{
    const STATUS_ENABLE = 'Y';

    private $order;

    /**
     * @var array 各單位總計
     */
    private $total = [];

    /**
     * @var array 全部小計
     */
    private $sumTotal = [
        'count' => 0,
        'total' => 0,
        'commission' => 0,
        'cash' => 0,
        'credit' => 0,
        'ATM' => 0,
    ];

    /**
     * ReportService constructor.
     */
    public function __construct(
        Order $order
    )
    {
        $this->order = $order;
    }

    /**
     * getSaleReport
     *
     * @param array $request
     * @return array
     */
    public function getSaleReport(array $request) :array
    {
        $orderData = $this->order->where('created_at', '>', $request['startDate'])
            ->where('created_at', '<', $request['endDate'])->get()->toArray();

        //判斷請求時間類型
        switch ($request['timeType']) {
            case 'year':
                $this->makeYearReport($orderData);
                break;
            case 'season':
                $this->makeSeasonReport($orderData);
                break;
            case 'month':
                $this->makeMonthReport($orderData);
                break;
            case 'day':
                $this->makeDayReport($orderData);
                break;
        }

        //累計加總資料
        $data['total'] = $this->total;
        $data['sumTotal'] = $this->sumTotal;

        return $data;
    }

    /**
     * getSaleHot 取熱銷商品
     *
     * @param array $request
     * @return mixed
     */
    public function getSaleHot(array $request)
    {
        // 沒有指定語系選擇預設語系
        $request['language'] = \Arr::get($request, 'language', config('system.defaultLanguage'));

        // 限制幾筆
        $request['limit'] = \Arr::get($request, 'limit', per_page());

        $orderProduct = app(OrderProduct::class);

        return $orderProduct->with(['product' => function ($query) use ($request) {
            return $query->where('product.status', self::STATUS_ENABLE);
        }])->with(['product.detail' => function ($query) use ($request) {
            return $query->where('language', $request['language']);
        }])->with('product.categories')
            ->groupBy('order_products.product_id')->limit($request['limit'])->get();
    }

    /**
     * makeYearReport 製作年報表
     *
     * @param array $data
     * @return string
     */
    private function makeYearReport($data)
    {
        foreach ($data as $key => $order) {
            // S ============== 各時間內區間計算 =================//
            $time = date('Y', strtotime($order['created_at']));

            if(!isset($this->total[$time])) {
                $this->total[$time] = [
                    'time' => $time,
                    'count' => 0,
                    'total' => 0,
                    'commission' => 0,

                    //@todo 未來要設計走支付模組
                    'cash' => 0,
                    'credit' => 0,
                    'ATM' => 0,
                ];
            }
            $this->total[$time]['count'] += 1;
            $this->total[$time]['total'] += $order['total'];

            //抽傭
            $this->total[$time]['commission'] += $order['commission'];

            //支付方式
            $this->total[$time][$order['payment_code']] += 1;
            // E ============== 各時間內區間計算 =================//

            // S ============== 總額小計加總 =================//
            $this->sumTotal['count'] += 1;
            $this->sumTotal['total'] += $order['total'];
            $this->sumTotal['commission'] += $order['commission'];
            $this->sumTotal[$order['payment_code']] += 1;
            // E ============== 總額小計加總 =================//
        }
    }

    /**
     * makeSeasonReport 製作季報表
     *
     * @param array $data
     * @return string
     */
    private function makeSeasonReport($data)
    {
        //月份對應季報表
        $seasonArray = [
            '01' => 1,
            '02' => 1,
            '03' => 1,
            '04' => 2,
            '05' => 2,
            '06' => 2,
            '07' => 3,
            '08' => 3,
            '09' => 3,
            '10' => 4,
            '11' => 4,
            '12' => 4,
        ];

        foreach ($data as $key => $order) {
            // S ============== 各時間內區間計算 =================//
            $time = explode('-' , date('Y-m', strtotime($order['created_at'])));

            //進行時間轉季節
            $season = $time[0] . '-' . $seasonArray[$time[1]];
            if(!isset($this->total[$season])) {
                $this->total[$season] = [
                    'time' => $time[0] . '(' . sprintf(trans('common.report.season'), $seasonArray[$time[1]]) . ')',
                    'count' => 0,
                    'total' => 0,
                    'commission' => 0,

                    //@todo 未來要設計走支付模組
                    'cash' => 0,
                    'credit' => 0,
                    'ATM' => 0,
                ];
            }
            $this->total[$season]['count'] += 1;
            $this->total[$season]['total'] += $order['total'];

            //抽傭
            $this->total[$season]['commission'] += $order['commission'];

            //支付方式
            $this->total[$season][$order['payment_code']] += 1;
            // E ============== 各時間內區間計算 =================//

            // S ============== 總額小計加總 =================//
            $this->sumTotal['count'] += 1;
            $this->sumTotal['total'] += $order['total'];
            $this->sumTotal['commission'] += $order['commission'];
            $this->sumTotal[$order['payment_code']] += 1;
            // E ============== 總額小計加總 =================//
        }
    }

    /**
     * makeMonthReport 製作月報表
     *
     * @param array $data
     * @return string
     */
    private function makeMonthReport($data)
    {
        foreach ($data as $key => $order) {
            // S ============== 各時間內區間計算 =================//
            $time = date('Y-m', strtotime($order['created_at']));

            if(!isset($this->total[$time])) {
                $this->total[$time] = [
                    'time' => $time,
                    'count' => 0,
                    'total' => 0,
                    'commission' => 0,

                    //@todo 未來要設計走支付模組
                    'cash' => 0,
                    'credit' => 0,
                    'ATM' => 0,
                ];
            }
            $this->total[$time]['count'] += 1;
            $this->total[$time]['total'] += $order['total'];

            //抽傭
            $this->total[$time]['commission'] += $order['commission'];

            //支付方式
            $this->total[$time][$order['payment_code']] += 1;
            // E ============== 各時間內區間計算 =================//

            // S ============== 總額小計加總 =================//
            $this->sumTotal['count'] += 1;
            $this->sumTotal['total'] += $order['total'];
            $this->sumTotal['commission'] += $order['commission'];
            $this->sumTotal[$order['payment_code']] += 1;
            // E ============== 總額小計加總 =================//
        }
    }

    /**
     * makeDayReport 製作日報表
     *
     * @param array $data
     * @return string
     */
    private function makeDayReport($data)
    {
        foreach ($data as $key => $order) {
            // S ============== 各時間內區間計算 =================//
            $time = date('Y-m-d', strtotime($order['created_at']));

            if(!isset($this->total[$time])) {
                $this->total[$time] = [
                    'time' => $time,
                    'count' => 0,
                    'total' => 0,
                    'commission' => 0,

                    //@todo 未來要設計走支付模組
                    'cash' => 0,
                    'credit' => 0,
                    'ATM' => 0,
                ];
            }
            $this->total[$time]['count'] += 1;
            $this->total[$time]['total'] += $order['total'];

            //抽傭
            $this->total[$time]['commission'] += $order['commission'];

            //支付方式
            $this->total[$time][$order['payment_code']] += 1;
            // E ============== 各時間內區間計算 =================//

            // S ============== 總額小計加總 =================//
            $this->sumTotal['count'] += 1;
            $this->sumTotal['total'] += $order['total'];
            $this->sumTotal['commission'] += $order['commission'];
            $this->sumTotal[$order['payment_code']] += 1;
            // E ============== 總額小計加總 =================//
        }
    }
}
