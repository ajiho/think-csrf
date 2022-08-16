<?php

namespace ajiho\middleware;

use think\exception\ValidateException;
use think\facade\Cookie;

class VerifyCsrfToken
{
    public function handle($request, \Closure $next)
    {
        //过滤掉不需要csrf验证的请求，第三个参数 true,代表要求数据类型是否一致
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $next($request);
        }
        $param_token_key = config('csrf.param_key');
        $cookie_token = Cookie::get(config('csrf.cookie_key'));
        $param_token = $request->has($param_token_key) ? $request->param($param_token_key) : $request->header('X-CSRF-TOKEN');

        if ($cookie_token === null || $param_token === null || $cookie_token !== $param_token) {
            throw new ValidateException('页面过期~');
        }
        return $next($request);
    }
}