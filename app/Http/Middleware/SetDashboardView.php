<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class SetDashboardView
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
        if (Auth::user()) {
            if (call_user_func_array('Request::is', ['dashboarrd*'])) {
                if (Session::has('blogview')) {
                    Cache::store('database')->put('_' . Auth::id() . '_blog_view', Session::get('blogview'), Config::get('cache.lifetime'));
                } else {
                    Session::put('blogview', 'table');
                    Cache::store('database')->put('_' . Auth::id() . '_blog_view', 'table', Config::get('cache.lifetime'));
                }
            }
        }
        return $next($request);
    }
}
