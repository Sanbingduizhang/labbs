<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * 三个判断:
         * 1. 如果用户已经扽古
         * 2. 并且还未认证email
         * 3. 并且访问的不是email验证相关的url或者退出的url
         */
        if ($request->user() &&
            ! $request->user()->hasVerifiedEmail() &&
            ! $request->is('email*','logout')) {

            //根据客户端返回的对应内容
            return $request->expectsJson()
                        ? abort(403, 'Your email address is not verified.')
                        : redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
