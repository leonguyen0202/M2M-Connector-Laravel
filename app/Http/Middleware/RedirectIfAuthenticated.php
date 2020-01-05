<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect(RouteServiceProvider::HOME);
        }

        $db = DB::table('localization')->get();

        for ($i=0; $i < count($db) ; $i++) { 
            $languages[($db[$i])->locale_code] = [($db[$i])->locale_name];
        }
        
        if (Session::has('applocale') and array_key_exists(Session::get('applocale'), $languages)) {
            App::setLocale(Session::get('applocale'));
        } else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            // setcookie(strtolower(env('APP_NAME')).'_language', Config::get('app.fallback_locale'), Config::get('session.lifetime'));
            App::setLocale(Config::get('app.fallback_locale'));
        }

        if (Auth::user()) {
            if (!Cache::has('_'.Auth::id().'_blog_data')) {
                Cache::store('database')->put('_'.Auth::id().'_blog_data', Auth::user()->has_blogs, Config::get('cache.lifetime'));
            }
        }

        return $next($request);
    }
}
