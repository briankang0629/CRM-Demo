<?php

namespace App\Http\Controllers\Admins\News;

use App\Http\Controllers\Controller;
use App\Services\News\NewsCategoryService;
use App\Transformers\Admins\News\NewsCategoryTransformer;

class NewsCategoryController extends Controller
{
    /**
     * @var $service
     */
    private $service;

    /**
     * NewsCategoryController constructor.
     *
     * @param NewsCategoryService $service
     */
    public function __construct (NewsCategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * lists
     *
     * @param \App\Http\Requests\Admins\News\Category\ListRequest $request
     * @return \Spatie\Fractal\Fractal
     */
    public function lists (\App\Http\Requests\Admins\News\Category\ListRequest $request)
    {
        $NewsCategories = $this->service->lists($request->toArray());

        return fractal($NewsCategories, NewsCategoryTransformer::class);
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
            $NewsCategory = $this->service->info($id);
            return response($NewsCategory);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }

    /**
     * store
     *
     * @param \App\Http\Requests\Admins\News\Category\UpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Admins\News\Category\UpdateRequest $request)
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
     * @param \App\Http\Requests\Admins\News\Category\UpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update (int $id, \App\Http\Requests\Admins\News\Category\UpdateRequest $request)
    {
        try {
            $this->service->update($request->toArray(), $id);

            return response(trans('common.updateSuccess'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
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
