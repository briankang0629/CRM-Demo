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
Route::post('/login', '\App\Http\Controllers\LoginController@login')->name('website.login');
Route::post('/logout', '\App\Http\Controllers\LoginController@logout')->name('website.logout');


/**
 * 商品專區
 */
Route::group(['namespace' => 'Products',  'as' => 'website::'], function () {
    /**
     * 商品列表
     */
    Route::group(['prefix' => 'product'] ,function () {
        Route::get('/', 'ProductController@lists')->name('product.list');
        Route::get('/{id}', 'ProductController@info')->name('product.info');
    });

    /**
     * 商品分類列表
     */
    Route::group(['prefix' => 'product/category'] ,function () {
        Route::get('/', 'ProductCategoryController@lists')->name('product.category.list');
        Route::get('/{id}', 'ProductCategoryController@info')->name('product.category.info');
    });
});

// 銷售熱銷商品 @todo
Route::get('/sale/hot', 'Reports\SaleReportController@getSaleHot')->name('sale.hot');
