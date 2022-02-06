<?php

namespace App\Http\Middleware\Auth;

use App\Exceptions\CustomException;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class Authenticate
{
    const ADMIN = 'admin';

    const USER = 'user';

    const SUPER = 'super';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        try {
            // 需要驗證卻沒帶header
            if (!$request->server('HTTP_AUTHORIZATION')) {
                throw new AuthenticationException(trans('auth.authenticate.error'));
            }

            if (is_admin()) {
                $identity = self::ADMIN;
            }

            if (is_website()) {
                $identity = self::USER;
            }

            // 驗證登入
            if (Auth::guard($identity)->check()) {
                if ($identity != self::SUPER) {
                    define('LOGIN_USER', Auth::guard($identity)->user()->toArray());
                }

                return $next($request);
            }

            //回傳錯誤
            throw new AuthenticationException(trans('auth.authenticate.failed'));
        } catch (AuthenticationException $e) {
            abort(401, $e->getMessage());
        } catch (CustomException $e) {
            abort(403, $e->getMessage());
        }
    }
}
