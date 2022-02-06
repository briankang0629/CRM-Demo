<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Sites\Host;
use App\Models\Sites\CustomDomain;

class HostProvider extends ServiceProvider
{
    private $host;

    const NOT_SHARE = 'N';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('host', function ($app) {
            return $this;
        });
    }

    /**
     * 返回當前網站
     * @return mixed
     */
    public function getCurrentHost()
    {
        return $this->host;
    }

    /**
     * 設置網站
     * 本地端 在 host 維持 localhost
     */
    public function setCurrentHost()
    {
        /* ==== 網址規則 =====/
         | 1. (基本方案)(host_code 必填) 後台： admin.{我方domain}.com 前台：客戶 {host_code}.{我方domain}.com
         | 2. (高級方案)(host_code 選填) 後台： admin.{客戶domain}.com 前台：客戶 www.{客戶domain}.com
         * ==== 網址規則 ===== */

        // 有客製化的網址 直接回傳
        if ($customDomain = $this->isCustomDomain()) {
            // 不為共用網址
            if ($customDomain->is_share === self::NOT_SHARE) {
                return $this->host = Host::find($customDomain->host_id);
            }
        }

        // 沒有客製化網址 要去 Host 表內搜尋
        $filed = 'sub_domain';

        // 是管理者端的情況 => 要去判斷有無host_code
        if (is_admin()) {
            // 網站代碼
            $filed = 'host_code';
            if (!$value = array_get(request()->header(), 'hostcode')) {
                abort(403, '錯誤！網站代碼不得為空');
            }

        } else {
            $value = sub_domain();
        }

        $host = Host::where($filed, $value)->first();

        if (is_null($host)) {
            abort(403, '無此網站代碼');
        }

        return $this->host = $host;
    }

    /**
     * @todo
     * 是否為非共版客製化網址
     * @return CustomDomain
     */
    public function isCustomDomain()
    {
        $customDomain = CustomDomain::where('domain', domain())->first();

        if (is_null($customDomain)) {
            abort(403, '非法網址請求');
        }

        return $customDomain;
    }

    // 預計開一個 host 對應多個 IP
    public function checkWhiteList()
    {
        return true;
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
}
