<?php

namespace App\Http\Controllers\Admins\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Reports\ProductViewRequest;
use App\Models\Products\Product;
use App\Services\Products\ProductService;
use http\Env\Request;

class ProductReportController extends Controller
{
    const SORT_DESC = 'desc';

    const PRODUCT_VIEW_LIMIT = 5;

    private $productService;

    /**
     * ProductReportController constructor.
     *
     * @param ProductService $service
     */
    public function __construct(ProductService $service)
    {
        $this->productService = $service;
    }

    /**
     * getProductView 商品觀看數
     */
    public function getProductView(ProductViewRequest $request)
    {
        $params = $request->getParams();

        if (!$language = array_get($params, 'language')) {
            $language = config('system.defaultLanguage');
        }

        \DB::enableQueryLog();
        $products = Product::with(['detail' => function ($query) use ($language) {
            return $query->where('language', $language);
        }])->orderBy('view', self::SORT_DESC)
            ->take(self::PRODUCT_VIEW_LIMIT)
            ->get();

        foreach ($products as $product) {
            $response[] = [
                'name' => $product->detail->name,
                'view' => $product->view,
            ];
        }

        return response($response);
    }
}
