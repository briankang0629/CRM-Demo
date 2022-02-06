<?php

namespace App\Services\Systems;
use App\Models\Systems\SystemMenu;

class SystemMenuService
{
    /**
     * @var object
     */
    private $systemMenu;

    /**
     * SystemMenuService constructor.
     *
     * @param SystemMenu $systemMenu
     */
    public function __construct(SystemMenu $systemMenu)
    {
        $this->systemMenu = $systemMenu;
    }

    /**
     * lists
     *
     * @return mixed
     */
    public function lists()
    {
        //宣告
        $menu = [];
        $children = [];

        // 子帳號再判斷權限
        if (LOGIN_USER['is_sub'] == 'Y') {
            $permission = \App\Models\Users\Permission::findOrFail(LOGIN_USER['permission_id'])->first()->toArray()['permission'];
        }

        //取系統選單列表
        $lists = $this->systemMenu->where('status', 'Y')->get()->toArray();

        //取母選單
        foreach ($lists as $key => $list) {
            if ($list['parent_id'] == 0) {
                $list['toggle'] = false;
                $menu[] = $list;
            } else {
                $children[] = $list;
            }
        }

        //取子選單
        foreach ($menu as $key => $value) {
            //預設無任何子選單
            $noChildrenList = true;

            foreach ($children as $sub) {
                if ($value['system_menu_id'] == $sub['parent_id']) {
                    $noChildrenList = false;

                    //判斷權限 若無開放則移除
                    if (isset($permission)) {
                        if (!isset($permission[$value['code']][$sub['code']]) || ($permission[$value['code']][$sub['code']] == 'N')) {
                            continue;
                        }
                    }

                    //存到子選單
                    $menu[$key]['children'][] = $sub;
                }
            }

            //檢視母選單下的子選單有無任一開放 若子選單都未開放 母選單則移除 ＠todo寫法需優化
            if ((!$noChildrenList) && (!isset($menu[$key]['children']))) {
                unset($menu[$key]);
            }
        }

        //重新排序
        sort($menu);

        //回傳
        return $menu;
    }
}
