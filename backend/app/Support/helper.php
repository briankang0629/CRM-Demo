<?php
// site

if (! function_exists('full_domain')) {
    /**
     * full_domain
     *
     * @return string
     */
    function full_domain() {
        $domain = request()->server('HTTP_HOST');
        return $domain;
    }
}

if (! function_exists('domain')) {
    /**
     * domain
     *
     * @return string
     */
    function domain() {
        $domain = full_domain();

        if (strpos($domain, 'localhost') !== false) {
            return 'localhost';
        }

        $explode = explode('.', $domain);

        if (count($explode) === 3) {
            return $explode[1] . '.' . $explode[2];
        } else if (count($explode) === 4) {
            return $explode[1] . '.' . $explode[2] . '.' . $explode[3];
        }
    }
}

// sub_domain
if (! function_exists('sub_domain')) {
    /**
     * sub_domain
     *
     * @return string
     */
    function sub_domain() {
        $domain = full_domain();

        if (strpos($domain, 'localhost') !== false) {
            return 'localhost';
        }

        $explode = explode('.', $domain);

        return $explode[0];
    }
}

if (! function_exists('get_client_ip')) {
    /**
     * get_client_ip
     *
     * @return string
     */
    function get_client_ip() {
        if ($clientIp = request()->server('HTTP_X_REAL_IP')) {
            return $clientIp;
        }

        return request()->getClientIp();
    }
}

if (! function_exists('is_local')) {
    /**
     * is_local
     *
     * @return string
     */
    function is_local() {
        return config('app.env') !== 'production';
    }
}

// host 網站核心
if (! function_exists('host')) {

    function host() {
        if (!app('host')->getCurrentHost()) {
            app('host')->setCurrentHost();
        }

        return app('host')->getCurrentHost();
    }
}

// host_code 網站代碼
if (! function_exists('host_code')) {

    function host_code() {
        return host()->host_code;
    }
}

// is_admin
if (! function_exists('is_admin')) {
    function is_admin () {
        return sub_domain() === 'admin';
    }
}

// is_admin
if (! function_exists('is_website')) {

    function is_website() {
        return sub_domain() !== 'admin';
    }
}

// is_super
if (! function_exists('is_super')) {

    function is_super() {
        if(!is_local()) {
            return sub_domain() === 'super';
        }

        return false; //@todo
    }
}

// is_share_domain
if (! function_exists('is_share_domain')) {

    function is_share_domain() {
        // domain array
    }
}



// Tool
if (! function_exists('token')) {
    /**
     * token 產生隨機碼
     *
     * @param string $length
     * @return string
     */
    function token($length = 32) {
        //產稱隨機碼
        $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        //max
        $max = strlen($string) - 1;

        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $token .= $string[mt_rand(0, $max)];
        }

        return $token;
    }
}

if (! function_exists('array_get')) {
    /**
     * array_get
     *
     * @return string
     */
    function array_get($array, $key, $default = null) {
        return \Arr::get($array, $key, $default);
    }
}

if (! function_exists('array_has')) {
    /**
     * array_has
     *
     * @return string
     */
    function array_has($array, $key) {
        return \Arr::has($array, $key);
    }
}

if (! function_exists('per_page')) {
    /**
     * per_page
     *
     * @return string
     */
    function per_page() {
        return request()->per_page ?? 10;
    }
}

if (! function_exists('permission')) {
    /**
     * permission
     *
     * @return bool
     */
    function permission($path, $permission) {
        return app('permission')->validate($path, $permission);
    }
}
