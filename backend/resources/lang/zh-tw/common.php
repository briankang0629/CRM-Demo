<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'delete' => [
        'success'            => '刪除成功',
        'failed'             => '刪除失敗',
        'failedRemoveMyself' => '刪除失敗：無法刪除自身帳號'
    ],

    'permission' => [
        'nameExists' => '權限名稱 已存在',
        'containAdmin' => '無法刪除，權限仍有歸類管理者',
    ],

    'productCategory' => [
        'nameExists' => '商品分類名稱 已存在',
        'containProduct' => '無法刪除，分類仍有歸類商品',
    ],

    'newsCategory' => [
        'nameExists' => '消息分類名稱 已存在',
        'containNews' => '無法刪除，分類仍有歸類消息',
    ],

    'user' => [
        'accountExists' => '帳號 已存在',
        'emailExists' => 'email 已存在',
    ],

    'userGroup' => [
        'nameExists' => '群組名稱 已存在',
        'default' => '預設群組',
    ],

    'admin' => [
        'accountExists' => '帳號 已存在',
        'emailExists' => 'email 已存在',
    ],

    'noExists' => '查無此資料',

    'attributes' => [
        'account'    => '帳號',
        'name'       => '名稱',
        'password'   => '密碼',
        'email'      => '電子郵件',
        'status'     => '狀態',
        'created_at' => '新增時間',
        'updated_at' => '修改時間'
    ],

    'createSuccess' => [
        'message' => '新增成功'
    ],

    'updateSuccess' => [
        'message' => '更新成功'
    ],

    'deleteSuccess' => [
        'message' => '刪除成功'
    ],

    'uploadSuccess' => [
        'message' => '上傳成功'
    ],

    'uploads' => [
        'invalidFormant' => '不允許的上傳格式',
    ],

    'report' => [
        'season' => '第 %s 季'
    ],

];
