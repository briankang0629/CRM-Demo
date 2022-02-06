<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Users\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    const ADMIN = 'admin';

    const USER = 'user';

    const SUPER = 'super';
    /**
     * @var
     */
    private $identity;

    /**
     * @var
     */
    private $user;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        if (is_admin()) {
            $this->identity = self::ADMIN;
        }

        if (is_website()) {
            $this->identity = self::USER;
        }

//        if (is_super()) {
//            $this->identity = self::SUPER;
//        }

        try {
            switch ($this->identity) {
                case self::ADMIN:
                    $this->user = app(\App\Services\Users\AdminService::class);
                    break;
                case self::USER:
//                    $this->user = new \App\Services\Users\UserService();
                    break;
                default:
//                    throw new \App\Exceptions\CustomException('錯誤請求');
                    break;
            }
        } catch (\App\Exceptions\CustomException $e) {
            abort(401, $e->getMessage());
        }
    }

    /**
     * login 登入
     *
     * @param LoginRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login (LoginRequest $request)
    {
        try {
            $token = $this->user->login($request);

            return response(['token' => $token]);
        } catch (\App\Exceptions\CustomException $e) {
            abort(401, $e->getMessage());
        }
    }

    /**
     * auth 驗證
     *
     * @param $identity
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function auth()
    {
        try {
            if ($this->identity == self::ADMIN) {
                $permission = Permission::findOrFail(LOGIN_USER['permission_id']);
            }

            return response([
                'user' => LOGIN_USER,
                'permission' => $permission->permission
            ]);
        } catch (\App\Exceptions\CustomException $e) {
            abort(401, $e->getMessage());
        }  catch (ModelNotFoundException $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * logout
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function logout()
    {
        if (Auth::guard($this->identity)->check()) {
            Auth::guard($this->identity)->user()->token()->delete();
        }

        return response(['message' => trans('auth.logout.success')]);
    }
}
