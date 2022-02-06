<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * $namespace
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->mapAdminRoutes();
//        $this->mapSuperRoutes();

        // 因無 domain 限制 必須放到最後面
        $this->mapWebsiteRoutes();

    }

    protected function mapAdminRoutes()
    {
        Route::group([
            'prefix' => 'api',
            'middleware' => 'api',
            'namespace' => $this->namespace . '\Admins',
            'domain' => 'admin.' . domain(),
        ], function () {
            require base_path('routes/' . config('app.version') . '/admin_route.php');
        });
    }

    protected function mapWebsiteRoutes()
    {
        Route::group([
            'prefix' => 'api',
            'middleware' => 'api',
            'namespace' => $this->namespace . '\Users',
        ], function () {
            require base_path('routes/' . config('app.version') . '/website_route.php');
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
