<?php

namespace App\Http\Middleware;

use App\Jobs\DatabaseCacheJob;
use App\Modules\Backend\Categories\Models\Category;
use App\Traits\BotmanTraits;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class DataCache
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
        $categories = Category::query()->chunk(10, function ($categories) {
            foreach ($categories as $key => $value) {
                if (!Cache::get('_' . $value->slug . '_top_posts') && !Cache::get('_' . $value->slug . '_top_contributors')) {
                    $job = (new DatabaseCacheJob($value))->delay(Carbon::now()->addSeconds(20));

                    dispatch($job);
                }
            }
        });

        if (Auth::user()) {
            if (!Cache::has('_' . Auth::id() . '_blog_data')) {
                Cache::store('database')->put('_' . Auth::id() . '_blog_data', Auth::user()->has_blogs, Config::get('cache.lifetime'));
            }

            if (!Cache::has('_' . Auth::id() . '_blog_view')) {
                Cache::store('database')->put('_' . Auth::id() . '_blog_view', 'table', Config::get('cache.lifetime'));
            }
        }

        return $next($request);
    }
}
