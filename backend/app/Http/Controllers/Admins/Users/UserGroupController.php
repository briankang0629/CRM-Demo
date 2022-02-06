<?php

namespace App\Http\Controllers\Admins\Users;

use App\Http\Controllers\Controller;
use Spatie\Fractal\Fractal;
use Illuminate\Support\Str;

class UserGroupController extends Controller
{
    /**
     * @var object
     */
    protected $service;

    /**
     * UserController constructor.
     */
    public function __construct(\App\Services\Users\UserGroupService $UserGroupService)
    {
        $this->service = $UserGroupService;
    }

    /**
     * lists 列表
     *
     * @param \App\Http\Requests\Admins\Users\UserGroup\ListRequest $request
     * @return Fractal
     */
    public function lists(\App\Http\Requests\Admins\Users\UserGroup\ListRequest $request)
    {
        $params = $request->getParams();

        $users = $this->service->lists($params);

        return fractal($users, \App\Transformers\Admins\Users\UserGroupTransformer::class);
    }

    /**
     * info
     *
     * @param \App\Http\Requests\Admins\Users\UserGroup\InfoRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function info(\App\Http\Requests\Admins\Users\UserGroup\InfoRequest $request, $id)
    {
        $user = $this->service->info($id);
        return response($user);
    }

    /**
     * store
     *
     * @param \App\Http\Requests\Admins\Users\UserGroup\StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Admins\Users\UserGroup\StoreRequest $request)
    {
        try {
            $params = $request->getParams();
            $this->service->update($params);

            return response(trans('common.createSuccess'));
        } catch (\App\Exceptions\CustomException $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * update
     *
     * @param \App\Http\Requests\Admins\Users\UserGroup\UpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Admins\Users\UserGroup\UpdateRequest $request, $id)
    {
        try {
            $params = $request->getParams();
            $this->service->update($params, $id);

            return response(trans('common.updateSuccess'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, trans('common.noExists'));
        }
    }

    /**
     * delete
     *
     * @param \App\Http\Requests\Admins\Users\UserGroup\DeleteRequest $request
     * @param $id
     */
    public function delete(\App\Http\Requests\Admins\Users\UserGroup\DeleteRequest $request, $ids)
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
