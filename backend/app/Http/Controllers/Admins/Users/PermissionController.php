<?php

namespace App\Http\Controllers\Admins\Users;

use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * @var object
     */
    protected $service;

    /**
     * AdminController constructor.
     */
    public function __construct(\App\Services\Users\PermissionService $PermissionService)
    {
        $this->service = $PermissionService;
    }

    /**
     * lists 列表
     *
     * @param \App\Http\Requests\Admins\Users\Permission\ListRequest $request
     * @return \Spatie\Fractal\Fractal
     */
    public function lists(\App\Http\Requests\Admins\Users\Permission\ListRequest $request)
    {
        $permission = $this->service->lists($request->toArray());

        return fractal($permission, \App\Transformers\Admins\Users\PermissionTransformer::class);
    }

    /**
     * info
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function info($id)
    {
        $permission = $this->service->info($id);
        return response($permission);
    }

    /**
     * store
     *
     * @param \App\Http\Requests\Admins\Users\Permission\StoreRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Admins\Users\Permission\StoreRequest $request)
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
     * @param \App\Http\Requests\Admins\Users\Permission\UpdateRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Admins\Users\Permission\UpdateRequest $request, $id)
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

    public function config ()
    {
        return response(config('system.permission'));
    }
}
