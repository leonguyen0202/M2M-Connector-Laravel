<?php

namespace App\Modules\Frontend\Event\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Categories\Models\Category;
use App\Modules\Backend\Events\Models\Event;
use App\Modules\Backend\Subscribes\Models\Subscribe;
use App\User;
use App\Traits\BotmanTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Config;
// use Illuminate\Support\Facades\Request as FacadeRequest;
// use Google\Cloud\BigQuery\BigQueryClient;

class EventController extends Controller
{
    use BotmanTraits;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        # parent::__construct();
        $this->special_event = 3;
        $this->is_followed = false;
    }

    public function index(Request $request)
    {
        $event_id = array();

        $special_events = Event::query()->where([
            ['promotion', '=', '1'],
            ['is_completed', '=', '0']
        ])->limit($this->special_event)->get();

        if (!$special_events->isEmpty()) {

            for ($i = 0; $i < count($special_events); $i++) {
                array_push($event_id, $special_events[$i]->id);
            }

        }
        
        $top_participated = Event::query()->where([
            ['promotion', '=', '0'],
            ['is_completed', '=', '0']
        ])->whereNotIn('id', $event_id)
        ->orderBy('participants', 'DESC')->limit(2)->get();

        if (!$top_participated->isEmpty()) {

            for ($i = 0; $i < count($top_participated); $i++) {
                array_push($event_id, $top_participated[$i]->id);
            }

        }

        $events = Event::query()->where([
            ['promotion', '=', '0'],
            ['is_completed', '=', '0']
        ])->whereNotIn('id', $event_id)->orderBy('created_at', 'DESC')->paginate(9);

        $contributors = Event::query()->select(
            DB::raw('COUNT(*) as total'),
            'author_id'
        )->groupBy('author_id')->orderBy('total', 'DESC')->limit(3)->get();

        if ($request->ajax()) {

            // if ($events instanceof Illuminate\Pagination\LengthAwarePaginator) {
            //     $view = view('Category::load')->with(['events' => $events])->render();

            //     if ($view == "") {
            //         return response()->json(['button' => render_load_more_button(__('frontend.no_more_event'))]);
            //     }

            // } else {
            //     return response()->json(['button' => render_load_more_button(__('frontend.no_more_event'))]);
            // }

            // return response()->json(['html' => $view]);
        };

        return view('Event::index')->with([
            'special_events' => $special_events,
            'top_participated' => $top_participated,
            'events' => $events,
            'contributors' => $contributors,
        ]);
    }

    public function detail($slug)
    {
        $event = Event::query()->where(Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug', $slug)->first();

        if ($event == null) {
            $event = Event::query()->where(Config::get('app.fallback_locale').'_slug', $slug)->first();
        }

        if ($event == null) {
            return redirect()->route('home.index');
        }

        if (Auth::user()) {
            $subscriber = Subscribe::query()->where([
                ['email', '=', Auth::user()->email]
            ])->first();

            $user = $event->author;

            if ($subscriber != null) {
                $this->is_followed = check_subscribe('_' . Auth::id() . '_users', $user, 'users');
            }
        }

        return view('Event::detail')->with([
            'event' => $event,
            'is_followed' => $this->is_followed,
        ]);
    }
}
