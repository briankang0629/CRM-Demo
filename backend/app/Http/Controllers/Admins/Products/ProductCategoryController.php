<?php

namespace App\Http\Controllers\Admins\Products;

use App\Http\Controllers\Controller;
use App\Services\Products\ProductCategoryService;
use App\Transformers\Admins\Products\ProductCategoryTransformer;

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
     * @param \App\Http\Requests\Admins\Products\Category\ListRequest $request
     * @return \Spatie\Fractal\Fractal
     */
    public function lists(\App\Http\Requests\Admins\Products\Category\ListRequest $request)
    {
        $params = array_filter($request->getParams());
        $params['isFamilyName'] = true;

        $productCategories = $this->service->lists($params);
        return fractal($productCategories, new ProductCategoryTransformer());
    }

    /**
     * info
     *
     * \App\Http\Requests\Admins\Products\Category\InfoRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function info(\App\Http\Requests\Admins\Products\Category\InfoRequest $request, $id)
    {
        try {
            $productCategory = $this->service->info($id);
            return response($productCategory);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }

    /**
     * store
     *
     * @param \App\Http\Requests\Admins\Products\Category\StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Admins\Products\Category\StoreRequest $request)
    {
        \DB::beginTransaction();
        try {
            $this->service->store($request->toArray());

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
     * @param \App\Http\Requests\Admins\Products\Category\UpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, \App\Http\Requests\Admins\Products\Category\UpdateRequest $request)
    {
        try {
            \DB::transaction(function () use ($id, $request) {
                $this->service->update($id, $request->toArray());
            });

            return response(trans('common.updateSuccess'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        } catch (\App\Exceptions\CustomException $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * delete
     *
     * @param $id
     */
    public function delete($ids)
    {
        try {
            $ids = \Illuminate\Support\Str::of($ids)->explode(',')->toArray();
            $this->service->delete($ids);

            return response(trans('common.deleteSuccess'));
        } catch(\App\Exceptions\CustomException $e) {
            abort(403, $e->getMessage());
        }
    }
}
