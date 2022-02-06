<?php

namespace App\Services\Users;

use App\Exceptions\CustomException;
use App\Models\Users\Permission;

class PermissionService
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * PermissionService constructor.
     *
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * lists 列表
     *
     * @param $request
     * @return mixed
     */
    public function lists(array $request)
    {
        return $this->permission->search($request)->paginate(per_page());
    }

    /**
     * info
     *
     * @param $id
     * @return Permission
     */
    public function info(int $id) :Permission
    {
        return $this->permission->findOrFail($id);
    }

    /**
     * store
     *
     * @param array $request
     * @return void
     * @throws CustomException
     */
    public function store(array $request) :void
    {
        if ($this->permission->search(['name' => $request['name']])->count()) {
            throw new CustomException(trans('common.permission.nameExists'));
        }

        $permission = $this->permission->create($request);

        //操作記錄
        $this->permission->writeLog(7, $permission);
    }

    /**
     * update
     *
     * @param int $id
     * @param array $request
     * @return void
     */
    public function update(int $id, array $request) :void
    {
        $permission = $this->permission->findOrFail($id);

        $permission->fill($request);
        $permission->save();

        //操作記錄
        $this->permission->writeLog(8, $permission);
    }

    /**
     * delete
     *
     * @param array $id
     * @return void
     */
    public function delete(array $ids) :void
    {
        $permissions = $this->permission->with('admin')->whereIn('permission_id', $ids);

        $permissions->each(function ($permission) {
            if ($permission->admin->count()) {
                throw new CustomException(trans('common.permission.containAdmin'));
            }
        });

        $this->permission->destroy($ids);

        //操作記錄
        $this->permission->writeLog(9);
    }
}
