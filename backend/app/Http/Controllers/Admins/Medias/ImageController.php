<?php

namespace App\Http\Controllers\Admins\Medias;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Medias\Image\ListRequest;
use App\Services\Medias\ImageService;
use App\Transformers\Admins\Medias\ImageTransformer;

class ImageController extends Controller
{
    /**
     * @var $service
     */
    private $service;

    /**
     * ImageController constructor.
     *
     * @param ImageService $service
     */
    public function __construct(ImageService $service)
    {
        $this->service = $service;
    }

    /**
     * lists
     *
     * @param ListRequest $request
     * @return \Spatie\Fractal\Fractal
     */
    public function lists(ListRequest $request)
    {
        $images = $this->service->lists($request->toArray());
        return fractal($images, ImageTransformer::class);
    }

    /**
     * info
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function info($id)
    {
        try {
            $image = $this->service->info($id);
            return response($image);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }

    /**
     * store
     *
     * @param \App\Http\Requests\Admins\Medias\Image\StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Admins\Medias\Image\StoreRequest $request)
    {
        \DB::beginTransaction();
        try {
            $result = $this->service->store($request->toArray());

            \DB::commit();
            return response(trans('common.uploadSuccess') + $result);
        } catch (\App\Exceptions\CustomException $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
        }
    }

    /**
     * createFolder
     *
     * @param \App\Http\Requests\Admins\Medias\Image\FolderStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function createFolder(\App\Http\Requests\Admins\Medias\Image\FolderStoreRequest $request)
    {
        \DB::beginTransaction();
        try {
            $this->service->createFolder($request->toArray());

            \DB::commit();
            return response(trans('common.createSuccess'));
        } catch (\App\Exceptions\CustomException $e) {
            \DB::rollback();
            abort(403, $e->getMessage());
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

    /**
     * setting
     * @return \Illuminate\Http\Response
     */
    public function setting() {
        return response(\DB::table('upload_folders')->select('name', 'code')->get());
    }
}
