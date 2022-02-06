<?php

namespace App\Http\Controllers\Admins\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductOptionValue;
use App\Services\Products\ProductService;
use App\Transformers\Admins\Products\ProductTransformer;
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
     * @param \App\Http\Requests\Admins\Products\ListRequest  $request
     * @return \Spatie\Fractal\Fractal
     */
    public function lists(\App\Http\Requests\Admins\Products\ListRequest  $request)
    {
        $products = $this->service->lists($request->toArray());

        return fractal($products, ProductTransformer::class);
    }

    /**
     * info
     *
     * @param $id
     * @return Fractal
     */
    public function info($id, \App\Http\Requests\Admins\Products\ListRequest $request)
    {
        try {
            $product = $this->service->info($id, $request->toArray());

            return fractal($product, new ProductTransformer('info'))->serializeWith(new \League\Fractal\Serializer\ArraySerializer());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }

    /**
     * store
     *
     * @param \App\Http\Requests\Admins\Products\UpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Admins\Products\UpdateRequest $request)
    {
        \DB::beginTransaction();
        try {
            $this->service->update($request->toArray());

            \DB::commit();
            return response(trans('common.createSuccess'));
        } catch (\App\Exceptions\CustomException $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    /**
     * update
     *
     * @param \App\Http\Requests\Admins\Products\UpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, \App\Http\Requests\Admins\Products\UpdateRequest $request)
    {
        \DB::beginTransaction();
        try {
            $this->service->update($request->toArray(), $id);

            \DB::commit();
            return response(trans('common.updateSuccess'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \DB::rollback();
            abort(404, trans('common.noExists'));
        }
    }

    /**
     * delete
     * @param $ids
     * @return \Illuminate\Http\Response
     */
    public function delete($ids)
    {
        \DB::beginTransaction();
        try {
            $ids = \Illuminate\Support\Str::of($ids)->explode(',')->toArray();
            $this->service->delete($ids);

            \DB::commit();
            return response(trans('common.deleteSuccess'));
        } catch(\App\Exceptions\CustomException $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    /**
     * deleteOption
     * @param $ids
     * @return \Illuminate\Http\Response
     */
    public function deleteOption($ids)
    {
        \DB::beginTransaction();
        try {
            $ids = \Illuminate\Support\Str::of($ids)->explode(',')->toArray();

            // optionValue
            $optionValueIds = ProductOptionValue::whereIn('product_option_id', $ids)
                ->pluck('product_option_value_id')->toArray();

            $this->service->deleteOption($ids);
            $this->service->deleteOptionValue($optionValueIds);

            \DB::commit();
            return response(trans('common.deleteSuccess'));
        } catch(\App\Exceptions\CustomException $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    /**
     * deleteOptionValue
     * @param $ids
     * @return \Illuminate\Http\Response
     */
    public function deleteOptionValue($ids)
    {
        \DB::beginTransaction();
        try {
            $ids = \Illuminate\Support\Str::of($ids)->explode(',')->toArray();
            $this->service->deleteOptionValue($ids);

            \DB::commit();
            return response(trans('common.deleteSuccess'));
        } catch(\App\Exceptions\CustomException $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }
}
