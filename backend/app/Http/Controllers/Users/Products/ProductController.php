<?php

namespace App\Http\Controllers\Users\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use App\Services\Products\ProductService;
use Spatie\Fractal\Fractal;

class ProductController extends Controller
{
    /**
     * @var $service
     */
    private $service;

    /**
     * ProductCategoryController constructor.
     *
     * @param ProductService $service
     */
    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * lists
     *
     * @param \App\Http\Requests\Users\Products\ListRequest  $request
     * @return \Spatie\Fractal\Fractal
     */
    public function lists(\App\Http\Requests\Users\Products\ListRequest  $request)
    {
        $params = $request->toArray();

        // 前台的商品必須要啟用
        $params['status'] = Product::ENABLE;
        $products = $this->service->lists($params);

        return fractal($products, \App\Transformers\Users\Products\ProductTransformer::class);
    }

    /**
     * info
     *
     * @param $id
     * @return Fractal
     */
    public function info($id, \App\Http\Requests\Users\Products\ListRequest $request)
    {
        $params = $request->getParams();

        if (!array_get($params, 'language')) {
            $params['language'] = config('app.language');
        }

        try {
            $product = $this->service->info($id, $params);

            return fractal($product, new \App\Transformers\Users\Products\ProductTransformer('info'))->serializeWith(new \League\Fractal\Serializer\ArraySerializer());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }

}
