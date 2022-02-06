<?php

namespace App\Services\Users;

use App\Models\Users\User;

class UserService
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserService constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * lists 列表
     *
     * @param $request
     * @return mixed
     */
    public function lists(array $request)
    {
        return $this->user->search($request)->paginate(per_page());
    }

    /**
     * info
     *
     * @param $id
     * @return User
     */
    public function info(int $id) :User
    {
        return $this->user->findOrFail($id);
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
        $user = $this->user->findOrNew($id);

        if (is_null($id)) {
            // 檢查帳號有無重覆
            if ($user->search(['account' => $request['account']])->count()) {
                throw new \App\Exceptions\CustomException(trans('common.user.accountExists'));
            }

            // 檢查email有無重覆
            if ($user->search(['email' => $request['email']])->count()) {
                throw new \App\Exceptions\CustomException(trans('common.user.emailExists'));
            }
        }

        // ip
        $request['ip'] = request()->ip();

        // 有密碼執行加密
        if (array_get($request, 'password')) {
            $request['password'] = bcrypt($request['password']);
        }

        // 搜尋並填充資料
        $user->fill(array_filter($request));
        $user->save();

        //操作記錄
        $this->user->writeLog($id ? 32 : 31, $user);
    }

    /**
     * delete
     *
     * @param array $id
     * @return User
     */
    public function delete(array $ids)
    {
        $this->user->destroy($ids);

        //操作記錄
        $this->user->writeLog(33);
    }
}
