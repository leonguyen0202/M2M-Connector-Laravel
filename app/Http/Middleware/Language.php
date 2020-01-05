<?php

namespace App\Http\Middleware;

use App\Traits\BotmanTraits;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Language
{
    use BotmanTraits;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $db = DB::table('localization')->get();

        for ($i = 0; $i < count($db); $i++) {
            $languages[($db[$i])->locale_code] = [($db[$i])->locale_name];
        }

        if (Session::has('applocale') and array_key_exists(Session::get('applocale'), $languages)) {
            App::setLocale(Session::get('applocale'));
        } else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            // setcookie(strtolower(env('APP_NAME')).'_language', Config::get('app.fallback_locale'), Config::get('session.lifetime'));
            App::setLocale(Config::get('app.fallback_locale'));
        }

        $jobs_table = DB::table('jobs')->count();

        if ($jobs_table == 0) {
            // DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::statement("ALTER TABLE jobs AUTO_INCREMENT = 1");
            // DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }

        return ($next($request))->cookie(
            strtolower(env('APP_NAME')) . '_language', App::getLocale(), config('session.lifetime'), config('session.path'), config('session.domain'), config('session.secure'), config('session.http_only')
        );
    }
}
