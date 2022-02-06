<?php

namespace App\Http\Controllers\Users\Products;

use App\Http\Controllers\Controller;
use App\Services\Products\ProductCategoryService;
use App\Transformers\Users\Products\ProductCategoryTransformer;

class ProductCategoryController extends Controller
{
    /**
     * @var $service
     */
    private $service;

    /**
     * ProductCategoryController constructor.
     *
     * @param ProductCategoryService $service
     */
    public function __construct(ProductCategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * lists
     *
     * @param \App\Http\Requests\Users\Products\Category\ListRequest $request
     * @return \Spatie\Fractal\Fractal
     */
    public function lists(\App\Http\Requests\Users\Products\Category\ListRequest $request)
    {
        $productCategories = $this->service->lists($request->toArray());

        $isTree = $request->has('isTree');

        return fractal($productCategories, new ProductCategoryTransformer($isTree));
    }

    /**
     * info
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function info ($id)
    {
        try {
            $productCategory = $this->service->info($id);
            return response($productCategory);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }
}
