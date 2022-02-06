<?php

namespace App\Providers;

use App\Models\Users\Permission;
use Illuminate\Support\ServiceProvider;

class PermissionProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('permission', function ($app) {
            return $this;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * validate
     * permission 權限驗證
     * 參數說明 :
     *      權限 $permission = [E,V] E => 要求寫入權限 , V => 要求查看權限, 同時允許查看權限, N => 無權限 | 順位 : E > V > N
     * @param string $path
     * @param string $permission
     * @return bool
     */
    public function validate($path = '', $requestPermission = 'E')
    {
        // 主帳號開放所有權限
        if (LOGIN_USER['is_sub'] === 'N') {
            return true;
        }

        // 取管理者及權限資料
        $admin = LOGIN_USER;
        $permissionData = Permission::findOrFail($admin['permission_id']);
        $permission = array_get($permissionData->permission, $path);

        // 無此路徑
        if (empty($permission)) {
            abort(403, trans('permission.setting.error'));
        }

        // 無權限
        if ($permission === 'N') {
            abort(403, trans('permission.permission.deny'));
        }

        // 要求寫入權限 卻只有閱讀權限
        if(($requestPermission === 'E') && ($permission === 'V')) {
            abort(403, trans('permission.permission.deny'));
        }

        return true;
    }

    /**
     * 網站開放設定驗證
     *
     * @param $permission
     * @param $setting
     * @param $menu
     * @param $subMenu
     */
    private function systemMenuValidate($permission, $setting, $menu, $subMenu) :void
    {
        //後台選單model
        //        $systemMenuModel = new SystemMenuModel();
        //
        //        //母選單
        //        if(!$menu = $systemMenuModel->getSystemMenuByCode($route[0])) {
        //            PublicFunction::error('9999-6', '', 999);
        //        }
        //
        //        //取子選單
        //        if(!$subMenu = $systemMenuModel->getSubMenuByParentId($menu['systemMenuId'])) {
        //            PublicFunction::error('9999-7', '', 999);
        //        }
        //
        //        //網站設定未開放指定子選單
        //        foreach ($subMenu as $key => $menu) {
        //            if($menu['code'] == $route[1]) {
        //                break;
        //            }
        //
        //            //最後一筆都沒有配對到 -> 網站未開放
        //            if($key === array_key_last($subMenu)) {
        //                PublicFunction::error('9999-8', '', 999);
        //            }
        //        }
    }
}
