<?php

namespace App\Services\Users;

use App\Exceptions\CustomException;
use App\Models\Users\Admin;
use Illuminate\Support\Facades\Auth;

class AdminService
{
    /**
     * @var Admin
     */
    private $admin;

    /**
     * AdminService constructor.
     *
     * @param Admin $admin
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * lists 列表
     *
     * @param $request
     * @return mixed
     */
    public function lists(array $request)
    {
        return $this->admin->search($request)->paginate(per_page());
    }

    /**
     * info
     *
     * @param $id
     * @return Admin
     */
    public function info(int $id) :Admin
    {
        return $this->admin->findOrFail($id);
    }

    /**
     * store
     *
     * @param $request
     * @return void
     */
    public function store (array $request) :void
    {
        // 檢查帳號有無重覆
        if ($this->admin->search(['account' => $request['account']])->count()) {
            throw new \App\Exceptions\CustomException(trans('common.admin.accountExists'));
        }

        // 檢查email有無重覆
        if ($this->admin->search(['email' => $request['account']])->count()) {
            throw new \App\Exceptions\CustomException(trans('common.admin.emailExists'));
        }


        // 有密碼執行加密
        if (isset($request['password'])) {
            $request['password'] = bcrypt($request['password']);
        }

        // ip
        $request['ip'] = request()->ip();

        // 搜尋並填充資料
        $admin = $this->admin->create($request);

        //操作記錄
        $this->admin->writeLog(4, $admin);
    }

    /**
     * update
     *
     * @param int $id
     * @param array $request
     * @return void
     */
    public function update (int $id, array $request) :void
    {
        $admin = $this->admin->findOrFail($id);

        // 有密碼執行加密
        if (isset($request['password'])) {
            $request['password'] = bcrypt($request['password']);
        }

        // 搜尋並填充資料
        $admin->fill($request);
        $admin->save();

        //操作記錄
        $this->admin->writeLog(5, $admin);
    }

    /**
     * delete
     *
     * @param array $id
     * @return Admin
     */
    public function delete (array $ids)
    {
        collect($ids)->each(function ($id) {
            if ($id == Auth::guard('admin')->user()->admin_id) {
                throw new CustomException(trans('common.delete.failedRemoveMyself'));
            }
        });

        $this->admin->destroy($ids);

        //操作記錄
        $this->admin->writeLog(6);
    }

    /**
     * login
     *
     * @param $request
     * @return string
     * @throws \App\Exceptions\CustomException
     */
    public function login ($request) :string
    {
        if(!$admin = Admin::where('account', $request->account)->first()) {
            throw new \App\Exceptions\CustomException('無此帳號');
        }

        if (!($admin->status === 'Y')) {
            throw new \App\Exceptions\CustomException('此帳號已禁用');
        }

        if(!\Hash::check($request->password, $admin->password)) {
            throw new \App\Exceptions\CustomException(trans('auth.password'));
        }

        // 更新ip 登入時間
        $admin->last_login_time = now();
        $admin->ip = get_client_ip();
        $admin->save();

        // 觸發管理者登入事件
        event(new \App\Events\AdminLogin($admin));

        return $admin->createToken('admins')->accessToken;
    }
}
