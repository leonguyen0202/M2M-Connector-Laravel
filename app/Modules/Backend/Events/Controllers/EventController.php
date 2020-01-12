<?php

namespace App\Modules\Backend\Events\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Backend\Categories\Models\Category;
use App\Modules\Backend\Events\Jobs\EventCRUDJob;
use App\Modules\Backend\Events\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        # parent::__construct();
        $this->middleware('auth');
        $this->fileName = 'default-background.jpg';
        $this->name = str_shuffle(Str::random(60)) . '_' . time();
        $this->languages = DB::table('localization')->select('locale_name', 'locale_code')->get();
    }

    /**
     * Test image download
     */
    protected function file_get_contents_curl($url) { 
        $ch = curl_init(); 
      
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_URL, $url); 
      
        $data = curl_exec($ch); 
        curl_close($ch); 
      
        return $data; 
    } 
    public function image_test()
    {
        // $url = 'https://www.imgbase.info/images/safe-wallpapers/miscellaneous/1_other_wallpapers/28634_1_other_wallpapers_danboard.jpg';

        $url = "http://hd.wallpaperswide.com/thumbs/ghosts_ii-t2.jpg";

        $contents = file_get_contents($url);

        $extension = (explode('.', basename($url) ))[ array_key_last( explode('.', basename($url) ) ) ];

        $this->fileName = $this->name . '.' . $extension;
        
        Storage::disk('upload')->put($this->fileName, $contents);

        return "DOWNLOADED";
    }

    public function event_categories_data_source(Request $request)
    {
        $search = $request->search;

        $data = array();

        if ($search == '') {
            $categories = Category::query()->select('title', 'slug')->get();
        } else {
            $categories = Category::query()->where([
                ['title', 'like', '%' . $search . '%'],
            ])->get();
        }

        foreach ($categories as $key => $value) {
            array_push($data, [
                'id' => $value->slug,
                'text' => $value->title,
            ]);
        }

        return response()->json($data);
    }

    /**
     * Render a listing of the events.
     *
     * @return \Illuminate\Http\Response
     */
    protected function render_event()
    {
        if (Cache::has('_' . Auth::id() . '_full_calendar_event')) {
            $events = Cache::get('_' . Auth::id() . '_full_calendar_event');
        } else {
            $events = array();

            $selectable_description = array();

            foreach ($this->languages as $key => $value) {
                array_push($selectable_description, $value->locale_code . '_title');
            }

            array_push($selectable_description, 'start');
            array_push($selectable_description, 'end');

            $data = Event::query()->where([
                ['author_id', '!=', Auth::id()],
                ['is_completed', '=', '0'],
                ['start', '>', Carbon::now()->toDateTimeString()],
            ])->orderBy('start', 'ASC')->get($selectable_description);

            foreach ($data as $key => $value) {
                array_push($events, [
                    "title" => $value->en_title,
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

                array_push($events, [
                    "title" => $value->en_title,
                    "start" => Carbon::parse($value->start)->toDateString(),
                    "end" => Carbon::parse($value->end)->toDateString(),
                    "className" => $className,
                ]);
            }

            Cache::store('database')->put('_' . Auth::id() . '_full_calendar_event', $events, Config::get('cache.lifetime'));
        }

        return response()->json($events);
    }

    public function check_title($title)
    {
        $event = Event::query()->where([
            [(Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_title'), '=', $title],
        ])->orWhere([
            [(Config::get('app.fallback_locale') . '_title'), '=', $title],
        ])->first();

        if ($event) {
            return response()->json(['error' => __('form.blog_title_unique')]);
        }
        return response()->json(['success' => 'Ok']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * End debug
         */

        return view('Events::index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('fileToUpload')) {
            $files = $request->file('fileToUpload');

            $this->fileName = $this->name . '.' . $files->getClientOriginalExtension();

            Storage::disk('upload')->putFileAs('', $files, $this->fileName);
        } else {
            return response()->json(['error' => 'Invalid image file!']);
        }

        $events = array();

        if (Cache::has('_' . Auth::id() . '_full_calendar_event')) {
            $events = Cache::get('_' . Auth::id() . '_full_calendar_event');
        }

        array_push($events, [
            "title" => $request->title,
            "start" => Carbon::parse($request->start)->toDateTimeString(),
            "end" => Carbon::parse($request->end)->toDateTimeString(),
            "className" => $request->className,
        ]);

        $array_job = [
            (Config::get('app.fallback_locale') . '_title') => $request->title,
            (Config::get('app.fallback_locale') . '_description') => $request->description,
            'background_image' => $this->fileName,
            'categories' => form_json_convert(explode(",", $request->categories), 'categories_id'),
            'author_id' => Auth::id(),
            'promotion' => '0',
            'is_completed' => '0',
            'qr_code' => $request->url,
            'type' => $request->type,
            'start' => $request->start,
            'end' => $request->end,
        ];

        $job = (new EventCRUDJob(null, $array_job, FacadeRequest::method()))->delay(Carbon::now()->addSeconds(rand(40, 60)));

        dispatch($job);

        Cache::store('database')->put('_' . Auth::id() . '_full_calendar_event', $events, Config::get('cache.lifetime'));

        return response()->json(['success' => 'Success']);
    }

    /**
     * Delete existing resource that match param
     *
     * @param string $title
     * @return \Illuminate\Http\Response
     */
    public function destroy($title)
    {
        if (!FacadeRequest::isMethod("DELETE")) {
            return response()->json(['error' => __('form.not_support_method')]);
        }

        $event = Event::query()->where([
            ['author_id', '=', Auth::id()],
        ])->where([
            [(Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_title'), '=', $title],
        ])->orWhere([
            [(Config::get('app.fallback_locale') . '_title'), '=', $title],
        ])->first();

        if (!$event) {
            return response()->json(['error' => __('form.data_not_exist')]);
        }

        $events_cache_data = $this->remove_cache($title);

        Cache::store('database')->put('_' . Auth::id() . '_full_calendar_event', $events_cache_data, Config::get('cache.lifetime'));

        $job = (new EventCRUDJob($event->id, array(), FacadeRequest::method()))->delay(Carbon::now()->addSeconds(rand(40, 60)));

        dispatch($job);

        return response()->json(['success' => 'Success']);
    }

    protected function remove_cache($title)
    {
        $cache = Cache::get('_' . Auth::id() . '_full_calendar_event');

        foreach ($cache as $key => $value) {
            // foreach ($value as $index => $item) {
            if ($value['title'] == $title) {
                unset($cache[$key]);
            }
            // }
        }

        return array_values($cache);
    }
}
