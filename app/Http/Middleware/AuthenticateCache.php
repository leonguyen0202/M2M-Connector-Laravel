<?php

namespace App\Http\Middleware;

use App\Modules\Backend\Events\Models\Event;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AuthenticateCache
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
            if (!Cache::has('_' . Auth::id() . '_blog_data')) {
                Cache::store('database')->put('_' . Auth::id() . '_blog_data', Auth::user()->has_blogs, Config::get('cache.lifetime'));
            }

            if (!Cache::has('_' . Auth::id() . '_blog_view')) {
                Cache::store('database')->put('_' . Auth::id() . '_blog_view', 'table', Config::get('cache.lifetime'));
            }

            if (!Cache::has('_' . Auth::id() . '_full_calendar_event')) {
                $results = array();

                $languages = DB::table('localization')->select('locale_name', 'locale_code')->get();

                $selectable_description = array();

                foreach ($languages as $key => $value) {
                    array_push($selectable_description, $value->locale_code . '_title');
                }

                array_push($selectable_description, 'start');
                array_push($selectable_description, 'end');

                $data = Event::query()->where([
                    ['author_id', '!=', Auth::id()],
                    ['type', '=', 'event'],
                    ['is_completed', '=', '0'],
                    ['start', '>', Carbon::now()->toDateTimeString()],
                ])->orderBy('start', 'ASC')->get($selectable_description);

                foreach ($data as $key => $value) {
                    array_push($results, [
                        "title" => $value->{Config::get('app.fallback_locale').'_title'},
                        "start" => Carbon::parse($value->start)->toDateTimeString(),
                        "end" => Carbon::parse($value->end)->toDateTimeString(),
                        "className" => 'event-green',
                        "editable" => false,
                    ]);
                }

                $data = Auth::user()->has_events;

                foreach ($data as $key => $value) {
                    if ($value->type == 'member') {
                        $className = 'event-orange';
                    } else {
                        $className = 'event-azure';
                    };
                    array_push($results, [
                        "title" => $value->{Config::get('app.fallback_locale').'_title'},
                        "start" => Carbon::parse($value->start)->toDateTimeString(),
                        "end" => Carbon::parse($value->end)->toDateTimeString(),
                        "className" => $className,
                    ]);
                }

                Cache::store('database')->put('_' . Auth::id() . '_full_calendar_event', $results, Config::get('cache.lifetime'));
            }
        }

        return $next($request);
    }
}
