<?php


use think\facade\Cookie;

if (!function_exists('csrf_field')) {

    function csrf_field()
    {
        $cck = config('csrf.cookie_key');

        if (Cookie::has($cck)) {
            $token = Cookie::get($cck);
        } else {//没有重新生成一份
            $token = call_user_func('md5', request()->server('REQUEST_TIME_FLOAT'));
            Cookie::forever($cck, $token, ['httponly' => true]);
        }
        return '<input type="hidden" name="' . config('csrf.param_key') . '" value="' . $token . '" />';
    }
}

if (!function_exists('csrf_meta')) {

    function csrf_meta()
    {
        $cck = config('csrf.cookie_key');

        if (Cookie::has($cck)) {
            $token = Cookie::get($cck);
        } else {//没有重新生成一份
            $token = call_user_func('md5', request()->server('REQUEST_TIME_FLOAT'));
            Cookie::forever($cck, $token, ['httponly' => true]);
        }
        return '<meta name="csrf-token" content="' . $token . '">';
    }
}


//直接返回token值
if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        //生成的key
        $cck = config('csrf.cookie_key');

        if (Cookie::has($cck)) {
            //有了就直接取出来
            return Cookie::get($cck);
        } else {
            //没有重新生成一份
            $token = call_user_func('md5', request()->server('REQUEST_TIME_FLOAT'));
            Cookie::forever($cck, $token, ['httponly' => true]);
            return $token;
        }
    }
}