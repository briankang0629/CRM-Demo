<?php

namespace App\Http\Controllers\Admins\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Spatie\Fractal\Fractal;

class AdminController extends Controller
{
    /**
     * @var object
     */
    protected $service;

    /**
     * AdminController constructor.
     */
    public function __construct(\App\Services\Users\AdminService $AdminService)
    {
        $this->service = $AdminService;
    }

    /**
     * lists 列表
     *
     * @param \App\Http\Requests\Admins\Users\Admin\ListRequest $request
     * @return Fractal
     */
    public function lists(\App\Http\Requests\Admins\Users\Admin\ListRequest $request)
    {
        $admins = $this->service->lists($request->toArray());

        return fractal($admins, \App\Transformers\Admins\Users\AdminTransformer::class);
    }

    /**
     * info
     *
     * @param \App\Http\Requests\Admins\Users\Admin\InfoRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function info(\App\Http\Requests\Admins\Users\Admin\InfoRequest $request, $id)
    {
        $admin = $this->service->info($id);
        return response($admin);
    }

    /**
     * store
     *
     * @param \App\Http\Requests\Admins\Users\Admin\StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Admins\Users\Admin\StoreRequest $request)
    {
        try {
            $this->service->store($request->toArray());

            return response(trans('common.createSuccess'));
        } catch (\App\Exceptions\CustomException $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * update
     *
     * @param \App\Http\Requests\Admins\Users\Admin\UpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Admins\Users\Admin\UpdateRequest $request, $id)
    {
        try {
            $this->service->update($id, $request->toArray());

            return response(trans('common.updateSuccess'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }

    /**
     * delete
     *
     * @param \App\Http\Requests\Admins\Users\Admin\DeleteRequest $request
     * @param $id
     */
    public function delete(\App\Http\Requests\Admins\Users\Admin\DeleteRequest $request, $ids)
    {
        try {
            $ids = Str::of($ids)->explode(',')->toArray();
            $this->service->delete($ids);

            return response(trans('common.deleteSuccess'));
        } catch(\App\Exceptions\CustomException $e) {
            abort(403, $e->getMessage());
        }
    }
}
