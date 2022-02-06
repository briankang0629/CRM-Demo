<?php

namespace App\Http\Controllers\Admins\News;

use App\Http\Controllers\Controller;
use App\Services\News\NewsService;
use App\Transformers\Admins\News\NewsTransformer;
use Spatie\Fractal\Fractal;

class NewsController extends Controller
{
    /**
     * @var $service
     */
    private $service;

    /**
     * NewsCategoryController constructor.
     *
     * @param NewsService $service
     */
    public function __construct (NewsService $service)
    {
        $this->service = $service;
    }

    /**
     * lists
     *
     * @param \App\Http\Requests\Admins\News\ListRequest $request
     * @return \Spatie\Fractal\Fractal
     */
    public function lists (\App\Http\Requests\Admins\News\ListRequest $request)
    {
        $news = $this->service->lists($request->toArray());

        return fractal($news, NewsTransformer::class);
    }

    /**
     * info
     *
     * @param $id
     * @return Fractal
     */
    public function info($id, \App\Http\Requests\Admins\News\ListRequest $request)
    {
        try {
            $news = $this->service->info($id, $request->toArray());

            return fractal($news, new NewsTransformer('info'))->serializeWith(new \League\Fractal\Serializer\ArraySerializer());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }

    /**
     * store
     *
     * @param \App\Http\Requests\Admins\News\UpdateRequest $request
     * @return  \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Admins\News\UpdateRequest $request)
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
     * @param \App\Http\Requests\Admins\News\UpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, \App\Http\Requests\Admins\News\UpdateRequest $request)
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
     *
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
}
