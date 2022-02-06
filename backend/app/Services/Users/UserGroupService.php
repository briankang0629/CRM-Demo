<?php

namespace App\Services\Users;

use App\Models\Users\UserGroup;

class UserGroupService
{
    /**
     * @var UserGroup
     */
    private $userGroup;

    /**
     * UserService constructor.
     *
     * @param UserGroup $userGroup
     */
    public function __construct(UserGroup $userGroup)
    {
        $this->userGroup = $userGroup;
    }

    /**
     * lists 列表
     *
     * @param $request
     * @return mixed
     */
    public function lists(array $request)
    {
        return $this->userGroup->search($request)->paginate(per_page());
    }

    /**
     * info
     *
     * @param $id
     * @return UserGroup
     */
    public function info(int $id) :UserGroup
    {
        return $this->userGroup->findOrFail($id);
    }

    /**
     * update
     *
     * @param int $id
     * @param array $request
     * @return void
     */
    public function update(array $request, int $id = null) :void
    {
        $userGroup = $this->userGroup->findOrNew($id);

        if (is_null($id)) {
            // 檢查名稱有無重覆
            if ($userGroup->search(['name' => $request['name']])->count()) {
                throw new \App\Exceptions\CustomException(trans('common.userGroup.nameExists'));
            }
        }

        // 搜尋並填充資料
        $userGroup->fill($request);
        $userGroup->save();

        //操作記錄
        $this->userGroup->writeLog($id ? 35 : 34, $userGroup);
    }

    /**
     * delete
     *
     * @param array $id
     */
    public function delete(array $ids)
    {
        $this->userGroup->destroy($ids);

        //操作記錄
        $this->userGroup->writeLog(36);
    }
}
