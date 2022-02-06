<?php

return [

    /*
    |--------------------------------------------------------------------------
    | System
    |--------------------------------------------------------------------------
    |
    | 系統會用到的代碼都放在這
    |
    */

    /**
     * 權限控管設定檔
     * 權限 $permission = [E,V] E => 要求寫入權限, V => 要求查看權限, 同時允許查看權限, N => 無權限 | 順位 : E > V > N
     */
    'permission' => [
        'dashboard' => 'N',
        'admin' => [
            'adminList' => 'N',
            'permission' => 'N',
        ],
        'user' => [
            'userList' => 'N',
            'userGroup' => 'N',
        ],
        'product' => [
            'productList' => 'N',
            'productCategory' => 'N',
            'productOption' => 'N',
            'productSpecification' => 'N',
        ],
        'stock' => [
            'stockCategory' => 'N',
        ],
        'news' => [
            'newsList' => 'N',
            'newsCategory' => 'N',
        ],
        'report' => [
            'saleReport' => 'N',
            'orderReport' => 'N',
        ],
        'order' => [
            'orderList' => 'N',
            'orderStatus' => 'N',
        ],
        'system' => [
            'setting' => 'N',
        ],
        'module' => [
            'moduleList' => 'N',
        ],
        'media' => [
            'image' => 'N',
        ],
        'logRecord' => [
            'logRecordList' => 'N',
        ],
    ],
    /**
     * 操作記錄設定檔
     * 操作介面 S=System(系統)、A=Admin(管理者)、U=User(會員)
     */
    'logRecordSetting' => [
        1 => ['type' => 'A', 'class' => 'createUser'],
        2 => ['type' => 'A', 'class' => 'updateUser'],
        3 => ['type' => 'A', 'class' => 'deleteUser'],
        4 => ['type' => 'A', 'class' => 'createAdmin'],
        5 => ['type' => 'A', 'class' => 'updateAdmin'],
        6 => ['type' => 'A', 'class' => 'deleteAdmin'],
        7 => ['type' => 'A', 'class' => 'createPermission'],
        8 => ['type' => 'A', 'class' => 'updatePermission'],
        9 => ['type' => 'A', 'class' => 'deletePermission'],
        10 => ['type' => 'A', 'class' => 'adminLogin'],
        11 => ['type' => 'U', 'class' => 'userLogin'],
        12 => ['type' => 'A', 'class' => 'adminLogout'],
        13 => ['type' => 'U', 'class' => 'userLogout'],
        14 => ['type' => 'A', 'class' => 'uploadImage'],
        15 => ['type' => 'A', 'class' => 'deleteImage'],
        16 => ['type' => 'A', 'class' => 'createImageFolder'],
        17 => ['type' => 'A', 'class' => 'createProduct'],
        18 => ['type' => 'A', 'class' => 'updateProduct'],
        19 => ['type' => 'A', 'class' => 'deleteProduct'],
        20 => ['type' => 'A', 'class' => 'deleteProductOption'],
        21 => ['type' => 'A', 'class' => 'deleteProductOptionValue'],
        22 => ['type' => 'A', 'class' => 'createNews'],
        23 => ['type' => 'A', 'class' => 'updateNews'],
        24 => ['type' => 'A', 'class' => 'deleteNews'],
        25 => ['type' => 'A', 'class' => 'createNewsCategory'],
        26 => ['type' => 'A', 'class' => 'updateNewsCategory'],
        27 => ['type' => 'A', 'class' => 'deleteNewsCategory'],
        28 => ['type' => 'A', 'class' => 'createProductCategory'],
        29 => ['type' => 'A', 'class' => 'updateProductCategory'],
        30 => ['type' => 'A', 'class' => 'deleteProductCategory'],
        31 => ['type' => 'A', 'class' => 'createUser'],
        32 => ['type' => 'A', 'class' => 'updateUser'],
        33 => ['type' => 'A', 'class' => 'deleteUser'],
        34 => ['type' => 'A', 'class' => 'createUserGroup'],
        35 => ['type' => 'A', 'class' => 'updateUserGroup'],
        36 => ['type' => 'A', 'class' => 'deleteUserGroup'],
    ],
    //系統預設語系
    'defaultLanguage' => 'zh-tw',

    //系統開放語系
    'language' => [
        'zh-tw',
        'en-us'
    ],

    'identityArray' => [
        'admin' => 'A',
        'user' => 'U',
        'super' => 'S',
    ],

    // 權限對應設定
    'permissionSetting' => [
        // 管理者區塊
        'admin.lists' => ['permission' => 'V', 'path' => 'admin/adminList'],
        'admin.info' => ['permission' => 'V', 'path' => 'admin/adminList'],
        'admin.store' => ['permission' => 'E', 'path' => 'admin/adminList'],
        'admin.update' => ['permission' => 'E', 'path' => 'admin/adminList'],
        'admin.delete' => ['permission' => 'E', 'path' => 'admin/adminList'],

        // 會員區塊
        'user.lists' => ['permission' => 'V', 'path' => 'user/userList'],
        'user.info' => ['permission' => 'V', 'path' => 'user/userList'],
        'user.store' => ['permission' => 'E', 'path' => 'user/userList'],
        'user.update' => ['permission' => 'E', 'path' => 'user/userList'],
        'user.delete' => ['permission' => 'E', 'path' => 'user/userList'],

        // 會員群組區塊
        'userGroup.lists' => ['permission' => 'V', 'path' => 'user/userGroup'],
        'userGroup.info' => ['permission' => 'V', 'path' => 'user/userGroup'],
        'userGroup.store' => ['permission' => 'E', 'path' => 'user/userGroup'],
        'userGroup.update' => ['permission' => 'E', 'path' => 'user/userGroup'],
        'userGroup.delete' => ['permission' => 'E', 'path' => 'user/userGroup'],

        // 權限區塊
        'permission.lists' => ['permission' => 'V', 'path' => 'admin/permission'],
        'permission.info' => ['permission' => 'V', 'path' => 'admin/permission'],
        'permission.store' => ['permission' => 'E', 'path' => 'admin/permission'],
        'permission.update' => ['permission' => 'E', 'path' => 'admin/permission'],
        'permission.delete' => ['permission' => 'E', 'path' => 'admin/permission'],
        'permission.config' => ['permission' => 'V', 'path' => 'admin/permission'],

        // 商品區塊
        'product.lists' => ['permission' => 'V', 'path' => 'product/productList'],
        'product.info' => ['permission' => 'V', 'path' => 'product/productList'],
        'product.store' => ['permission' => 'E', 'path' => 'product/productList'],
        'product.update' => ['permission' => 'E', 'path' => 'product/productList'],
        'product.delete' => ['permission' => 'E', 'path' => 'product/productList'],

        // 商品分類區塊
        'productCategory.lists' => ['permission' => 'V', 'path' => 'product/productCategory'],
        'productCategory.info' => ['permission' => 'V', 'path' => 'product/productCategory'],
        'productCategory.store' => ['permission' => 'E', 'path' => 'product/productCategory'],
        'productCategory.update' => ['permission' => 'E', 'path' => 'product/productCategory'],
        'productCategory.delete' => ['permission' => 'E', 'path' => 'product/productCategory'],

        // 消息區塊
        'news.lists' => ['permission' => 'V', 'path' => 'news/newsList'],
        'news.info' => ['permission' => 'V', 'path' => 'news/newsList'],
        'news.store' => ['permission' => 'E', 'path' => 'news/newsList'],
        'news.update' => ['permission' => 'E', 'path' => 'news/newsList'],
        'news.delete' => ['permission' => 'E', 'path' => 'news/newsList'],

        // 消息分類區塊
        'newsCategory.lists' => ['permission' => 'V', 'path' => 'news/newsCategory'],
        'newsCategory.info' => ['permission' => 'V', 'path' => 'news/newsCategory'],
        'newsCategory.store' => ['permission' => 'E', 'path' => 'news/newsCategory'],
        'newsCategory.update' => ['permission' => 'E', 'path' => 'news/newsCategory'],
        'newsCategory.delete' => ['permission' => 'E', 'path' => 'news/newsCategory'],

        // 圖片區塊
        'image.lists' => ['permission' => 'V', 'path' => 'media/image'],
        'image.info' => ['permission' => 'V', 'path' => 'media/image'],
        'image.store' => ['permission' => 'E', 'path' => 'media/image'],
        'image.delete' => ['permission' => 'E', 'path' => 'media/image'],
        'image.setting' => ['permission' => 'V', 'path' => 'media/image'],
        'image.createFolder' => ['permission' => 'V', 'path' => 'media/image'],

        // 報表
        'report.saleReport' => ['permission' => 'V', 'path' => 'report/saleReport'],
    ],
];
