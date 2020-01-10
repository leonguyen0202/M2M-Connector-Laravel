<?php

namespace App\Modules\Frontend\Home\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Events\Models\Event;
use App\Modules\Backend\Subscribes\Models\Subscribe;
use App\Modules\Backend\Subscribes\Jobs\SubscribeJob;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use JavaScript;
use Illuminate\Support\Str;
use Inani\OxfordApiWrapper\OxfordWrapper;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Traits\BotmanTraits;
use App\Modules\Backend\Categories\Models\Category;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
// use Illuminate\Support\Facades\Request;
// use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Support\Facades\Schema;
use App\Jobs\SubscribeActionJob;
use Carbon\Carbon;

class HomeController extends Controller
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
        $this->slider = 5;

        $this->language = App::getLocale();
    }

    protected function converting_model($plural, $singular)
    {
        return "App\\Modules\\Backend\\" . $plural . "\\Models\\" . $singular;
    }

    public function switchLang($locale)
    {
        $db = DB::table('localization')->get();

        for ($i=0; $i < count($db) ; $i++) { 
            $languages[($db[$i])->locale_code] = [($db[$i])->locale_name];
        }

        if (array_key_exists($locale, $languages)) {
            Session::put('applocale', $locale);
        }

        return redirect()->back()->cookie(
            strtolower(env('APP_NAME')).'_language', $locale, config('session.lifetime'), config('session.path'), config('session.domain'), config('session.secure'), config('session.http_only')
        );
    }

    protected function getColumns()
    {
        return Schema::getColumnListing('blogs');
    }

    public function index(Request $request)
    {
        // $res = $client->request('GET', env('OXFORD_API_BASE_URI').'/thesaurus/en/popular');

        // $res = $client->request('GET', env('OXFORD_API_BASE_URI').'/entries/en/alice');

        // echo $res->getStatusCode();

        // die;

        // $json = json_decode($res->getBody()->getContents(), true);

        // $results = $json['results'][0]['lexicalEntries'][0]['entries'][0]['senses'];

        // for ($i=0; $i < count($results); $i++) { 
        //     // echo $results[$i]['definitions'][0]. "<br/>";

        //     array_push($antonyms, $results[$i]['definitions'][0]);
        // }

        // return $results[0]['definitions'][0];

        

        // return $results;

        // dd($antonyms);
        
        // die;

        // echo gettype($json);

        /**
         * End debug
         */

        // dd(Cache::get('_'.Auth::id().'_blogs'));

        $posts = Blog::query()->orderBy('created_at', 'DESC')->paginate(9);

        $top_posts = Blog::query()->orderBy('visits', 'DESC')->limit(2)->get();

        $event_id = array();

        $events = Event::where([
            ['type', '=', 'event'],
            ['is_completed', '=', '0'],
        ])->get()->count();

        if ($events >= $this->slider) {

            $promo_count = Event::query()->where([
                ['type', '=', 'event'],
                ['promotion', '=', '1'],
                ['is_completed', '=', '0'],
            ])->select(
                DB::raw('COUNT(*) as total'),
                'promotion',
                'is_completed'
            )->groupBy('promotion', 'is_completed')
            ->first();

            if ($promo_count) {
                $promo_event = Event::where([
                    ['type', '=', 'event'],
                    ['promotion', '=', '1'],
                    ['is_completed', '=', '0'],
                ])->get();

                for ($i = 0; $i < $promo_count->total; $i++) {
                    array_push($event_id, $promo_event[$i]->id);
                }
            }

            $free_event = Event::query()->where([
                ['type', '=', 'event'],
                ['promotion', '=', '0'],
                ['is_completed', '=', '0'],
            ])->whereNotIn('id', $event_id)
            ->inRandomOrder()
            ->limit($this->slider - count($event_id))
            ->get();

            for ($i = 0; $i < count($free_event); $i++) {
                array_push($event_id, $free_event[$i]->id);
            }

            $sliders = Event::query()->whereIn('id', $event_id)->get();

        } else {
            $sliders = array();

            $free_event = Event::query()->where([
                ['type', '=', 'event'],
                ['is_completed', '=', '0'],
            ])->get();

            for ($i = 0; $i < count($free_event); $i++) {
                array_push($sliders, $free_event[$i]);
            }

            $slider_post = Blog::query()->inRandomOrder()->limit($this->slider - count($sliders))->get();

            for ($i = 0; $i < count($slider_post); $i++) {
                array_push($sliders, $slider_post[$i]);
            }
        }

        if ($request->ajax()) {
            $view = view('Home::load')->with(['posts' => $posts])->render();

            if ($view == "") {
                return response()->json(['button' => render_load_more_button(__('frontend.no_more_post'))]);
            }

            return response()->json(['html' => $view]);
        };

        $paginator = $posts->toArray();

        return view('Home::index')->with([
            'posts' => $posts,
            'top_posts' => $top_posts,
            'sliders' => $sliders,
        ]);
    }

    public function action(Request $request)
    {
        if (!Auth::user()) {
            return redirect()->back();
        }

        $user = Auth::user();

        if ($user->email != $request->input('email')) {
            return redirect()->back();
        }

        $subscriber = Subscribe::where('email', $user->email)->first();

        $url_string = trim(str_replace(env('APP_URL'), '', url()->previous()), '/');

        $url = explode("/", $url_string);

        if ( (strtolower($request->input('type')) == 'blogs' && $url[0] == 'blog') || (strtolower($request->input('type')) == 'events' && $url[0] == 'event') ) {
            $record = ($this->converting_model( Str::plural(ucfirst( $url[0] )), Str::singular(ucfirst( $url[0] )) ))::query()->where([
                [Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug', '=', $url[1]]
            ])->orWhere([
                [Config::get('app.fallback_locale').'_slug', '=', $url[1]]
            ])->first();
        } else if (strtolower($request->input('type')) == 'categories' && $url[0] == 'category') {
            $record = Category::query()->where([
                ['slug', '=', $url[1]]
            ])->first();
        } else {
            if (strtolower($request->input('type')) == 'users' && $url[0] == 'user') {
                # follow at profile page
            } else if (strtolower($request->input('type')) == 'users') {
                # follow user at blog or event page
                $record = ($this->converting_model( Str::plural(ucfirst( $url[0] )), Str::singular(ucfirst( $url[0] )) ))::query()->where([
                    [Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug', '=', $url[1]]
                ])->orWhere([
                    [Config::get('app.fallback_locale').'_slug', '=', $url[1]]
                ])
                ->first();

                if (!$record) {
                    return redirect()->back()->with('errors', 'Data is not exist!');
                }

                $record = $record->author;
            } else {
                return redirect()->back()->with('errors', 'Data is not exist!');
            }
        }

        if (!$record) {
            return redirect()->back()->with('errors', 'Data is not exist!');
        }
        
        $result = array();

        if ( Cache::has('_' . Auth::id() .  '_' . strtolower($request->input('type'))) ) {
            $cache_data = Cache::get('_' . Auth::id() . '_' . strtolower($request->input('type'))); // Array

            if (gettype(array_search($record->id, $cache_data)) == 'integer') {
                if (count($cache_data) < 2) {
                    $cache_data = array();
                } else {
                    unset($cache_data[array_search($record->id, $cache_data)]);
                }
            } else {
                array_push($result, $record->id);
            }
            
            foreach ($cache_data as $key => $value) {
                array_push($result, $value);
            }
        } else {
            array_push($result, $record->id);
        }

        Cache::store('database')->put('_'. Auth::id().'_'. strtolower($request->input('type')), $result, Config::get('cache.lifetime'));

        $job = ( new SubscribeJob($user, strtolower($request->input('type')), $result ) )->delay(Carbon::now()->addSeconds(5));

        dispatch($job);

        return redirect()->back();
    }

    public function backup(Request $request)
    {
        if (!Auth::user()) {
            return redirect()->back();
        }

        $user = Auth::user();

        if ($user->email != $request->input('email')) {
            return redirect()->back();
        }

        $subscriber = Subscribe::where('email', $user->email)->first();

        $url_string = trim(str_replace(env('APP_URL'), '', url()->previous()), '/');

        $url = explode("/", $url_string);
        
        if (strtolower($request->input('type')) == 'blogs' || strtolower($request->input('type')) == 'events' || strtolower($request->input('type')) == 'categories' || strtolower($request->input('type')) == 'users') {
            $column = Str::plural(strtolower($request->input('type')));

            if ($url[0] == 'blog' || $url[0] == 'event') {
                    
                $record = ($this->converting_model( Str::plural(ucfirst( $url[0] )), Str::singular(ucfirst( $url[0] )) ))::query()->where([
                    [Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug', '=', $url[1]]
                ])->first();

                if ($record == null) {
                    $record = ($this->converting_model( Str::plural(ucfirst( $url[0] )), Str::singular(ucfirst( $url[0] )) ))::query()->where([
                        [Config::get('app.fallback_locale').'_slug', '=', $url[1]]
                    ])->first();
                }

                if ($column == 'users') {
                    $record = $record->author;
                }
            } else if ($url[0] == 'category') {
                $record = ($this->converting_model( Str::plural(ucfirst( $url[0] )), Str::singular(ucfirst( $url[0] )) ))::query()->where([
                    ['slug', '=', $url[1]]
                ])->first();
            } else if ($url[0] == 'user') {
                $record = User::where([
                    ['name', '=', $url[1]]
                ])->first();
            } 
            else {
                return redirect()->back();
            }

            if ($record == null) {
                return redirect()->back();
            }

        } else {
            return redirect()->back();
        }

        $cache_value = array();

        if (Cache::has('_' . Auth::id() .  '_' . strtolower($request->input('type')))) {
            $cache = Cache::get('_' . Auth::id() . '_' . strtolower($request->input('type')));
            
            $original = array();

            $duplicate = array();

            array_push($original, $record->id);

            foreach ($cache as $key => $value) {
                array_push($original, $value);

                if ($value == $record->id) {
                    array_push($duplicate, $value);
                }
            }

            $cache_value = array_diff($original, $duplicate);
        } else {
            array_push($cache_value, $record->id);
        }

        Cache::store('database')->put('_'. Auth::id().'_'. strtolower($request->input('type')), $cache_value, Config::get('cache.lifetime'));

        $job = (new SubscribeActionJob($request->input('type'), $url, $subscriber, Auth::user(), Cookie::get(strtolower(env('APP_NAME')) . '_language') ))->delay(Carbon::now()->addSeconds(20));

        dispatch($job);

        return redirect()->back();
    }

    public function subscribe(Request $request)
    {
        if (Auth::user()) {
            $user = Auth::user();
            if ($user->email != $request->input('subscribe_email')) {
                return response()->json(['error' => __('frontend.wrong_data')]);
            }
        }

        $subscriber = Subscribe::where('email', $request->input('subscribe_email'))->first();

        if ($subscriber) {
            return response()->json(['error' => __('frontend.already_subscriber')]);
        }

        $user = User::where('email', $request->input('subscribe_email'))->first();

        if ($user) {
            Subscribe::create([
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        } else {
            Subscribe::create([
                'email' => $request->input('subscribe_email'),
            ]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => __('frontend.success_subscribe')]);
        } else {
            return redirect()->back();
        }
    }

    protected function get_slug_url()
    {
        $url_string = trim(str_replace(env('APP_URL'), '', url()->previous()), '/');

        $url = explode("/", $url_string);

        return $url[1];
    }
}
