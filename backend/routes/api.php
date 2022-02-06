<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//指定參數正則法
Route::pattern('id', '[0-9]+');
Route::pattern('ids', '[0-9,]+');

/**
 * 登入專區
 */
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');
Route::get('/getServer', function () {
    dump(request()->header());
    $server = $_SERVER;
    unset($server['APP_KEY']);
    unset($server['DB_CONNECTION']);
    unset($server['DB_HOST']);
    unset($server['DB_PORT']);
    unset($server['DB_DATABASE']);
    unset($server['DB_USERNAME']);
    unset($server['DB_PASSWORD']);
    dd($server);
});
/**
 * 套用驗證權限
 */
Route::group(['middleware' => ['authenticate']], function () {

    // Auth 驗證
    Route::get('/auth', 'Auth\LoginController@auth');

    /**
     * 套用驗證權限管理
     */
    Route::group(['middleware' => ['permission.validate']], function () {

        /**
         * User 使用者專區
         */
        Route::group(['namespace' => 'Users'], function () {

            /**
             * Admin 管理者專區
             */
            Route::group(['prefix' => 'admin'] ,function () {
                Route::get('/', 'AdminController@lists')->name('admin.lists');
                Route::get('/{id}', 'AdminController@info')->name('admin.info');
                Route::post('/', 'AdminController@store')->name('admin.store');
                Route::put('/{id}', 'AdminController@update')->name('admin.update');
                Route::delete('/{ids}', 'AdminController@delete')->name('admin.delete');
            });

            /**
             * 權限列表
             */
            Route::group(['prefix' => 'permission'] ,function () {
                Route::get('/', 'PermissionController@lists')->name('permission.lists');
                Route::get('/{id}', 'PermissionController@info')->name('permission.info');
                Route::post('/', 'PermissionController@store')->name('permission.store');
                Route::put('/{id}', 'PermissionController@update')->name('permission.update');
                Route::delete('/{ids}', 'PermissionController@delete')->name('permission.delete');
                Route::get('/config', 'PermissionController@config')->name('permission.config');
            });
        });

        /**
         * 商品專區
         */
        Route::group(['namespace' => 'Products'], function () {
            /**
             * 商品列表
             */
            Route::group(['prefix' => 'product'] ,function () {
                Route::get('/', 'ProductController@lists')->name('product.lists');
                Route::get('/{id}', 'ProductController@info')->name('product.info');
                Route::post('/', 'ProductController@store')->name('product.store');
                Route::put('/{id}', 'ProductController@update')->name('product.update');
                Route::delete('/{ids}', 'ProductController@delete')->name('product.delete');
                Route::delete('/option/{ids}', 'ProductController@deleteOption')->name('product.deleteOption');
                Route::delete('/option/value/{ids}', 'ProductController@deleteOptionValue')->name('product.deleteOptionValue');
            });

            /**
             * 商品分類列表
             */
            Route::group(['prefix' => 'product/category'] ,function () {
                Route::get('/', 'ProductCategoryController@lists')->name('productCategory.lists');
                Route::get('/{id}', 'ProductCategoryController@info')->name('productCategory.info');
                Route::post('/', 'ProductCategoryController@store')->name('productCategory.store');
                Route::put('/{id}', 'ProductCategoryController@update')->name('productCategory.update');
                Route::delete('/{ids}', 'ProductCategoryController@delete')->name('productCategory.delete');
            });
        });

        /**
         * 消息專區
         */
        Route::group(['namespace' => 'News'], function () {
            /**
             * 消息列表
             */
            Route::group(['prefix' => 'news'] ,function () {
                Route::get('/', 'NewsController@lists')->name('news.lists');
                Route::get('/{id}', 'NewsController@info')->name('news.info');
                Route::post('/', 'NewsController@store')->name('news.store');
                Route::put('/{id}', 'NewsController@update')->name('news.update');
                Route::delete('/{ids}', 'NewsController@delete')->name('news.delete');
            });

            /**
             * 消息分類列表
             */
            Route::group(['prefix' => 'news/category'] ,function () {
                Route::get('/', 'NewsCategoryController@lists')->name('newsCategory.lists');
                Route::get('/{id}', 'NewsCategoryController@info')->name('newsCategory.info');
                Route::post('/', 'NewsCategoryController@store')->name('newsCategory.store');
                Route::put('/{id}', 'NewsCategoryController@update')->name('newsCategory.update');
                Route::delete('/{ids}', 'NewsCategoryController@delete')->name('newsCategory.delete');
            });
        });

        /**
         * 多媒體專區
         */
        Route::group(['namespace' => 'Medias', 'prefix' => 'media'], function () {
            /**
             * 圖片專區
             */
            Route::group(['prefix' => 'image'] ,function () {
                Route::get('/', 'ImageController@lists')->name('image.lists');
                Route::get('/{id}', 'ImageController@info')->name('image.info');
                Route::post('/', 'ImageController@store')->name('image.store');
                Route::delete('/{ids}', 'ImageController@delete')->name('image.delete');
                Route::get('/setting', 'ImageController@setting')->name('image.setting');
                Route::post('/create/folder', 'ImageController@createFolder')->name('image.createFolder');
            });
        });
    });

    /**
     * System 系統相關
     */
    Route::group(['namespace' => 'Systems'] ,function () {
        /**
         * LogRecord 操作記錄
         */
        Route::group(['prefix' => 'logRecord'] ,function () {
            Route::get('/', 'LogRecordController@lists')->name('logRecord.lists');
            Route::get('/{id}', 'LogRecordController@info')->name('logRecord.info');
            Route::get('/setting', 'LogRecordController@setting')->name('logRecord.setting');
        });

        /**
         * SystemMenu 系統選單
         */
        Route::group(['prefix' => 'system'] ,function () {
            Route::group(['prefix' => 'menu'] ,function () {
                Route::get('/', 'SystemMenuController@lists')->name('systemMenu.lists');
            });
        });
    });

    // report 報表
    Route::group(['namespace' => 'Reports'] ,function () {
        // 月報表
        Route::group(['prefix' => 'report'] ,function () {
            Route::get('/saleReport', 'SaleReportController@getSaleReport')->name('report.saleReport');
        });
    });

});

/*
|--------------------------------------------------------------------------
| 未登入請求
|--------------------------------------------------------------------------
| 會員端在前台尚未登入的所有請求
*/

// 銷售熱銷商品
Route::get('/sale/hot', 'Reports\SaleReportController@getSaleHot')->name('saleHot');
