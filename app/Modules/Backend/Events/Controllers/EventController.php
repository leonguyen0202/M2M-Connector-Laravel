<?php

namespace App\Modules\Backend\Events\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Events\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
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
            return response()->json(['error' => 'Title has been taken']);
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
     * @param array $value [title, start,end]
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('fileToUpload')) {
            $files = $request->file('fileToUpload');

            $this->fileName = $this->name . '.' . $files->getClientOriginalExtension();

            return response()->json([ 'image' => $this->fileName ]);
        } else {
            return response()->json(['data' => $request->all()]);
        }
        
        /**
         * End debug
         */

        $content_dummy = Blog::inRandomOrder()->first();

        if (Cache::has('_' . Auth::id() . '_full_calendar_event')) {
            $events = Cache::get('_' . Auth::id() . '_full_calendar_event');

            array_push($events, [
                "title" => $request->title,
                "start" => Carbon::parse($request->start)->toDateTimeString(),
                "end" => Carbon::parse($request->end)->toDateTimeString(),
                "className" => $request->className,
            ]);
        } else {
            $events = array();

            array_push($events, [
                "title" => $request->title,
                "start" => Carbon::parse($request->start)->toDateTimeString(),
                "end" => Carbon::parse($request->end)->toDateTimeString(),
                "className" => $request->className,
            ]);
        }

        $array_job = [
            'en_title' => $request->title,
            'en_description' => $content_dummy->en_description,
            'background_image' => random_image(['disk' => 'public', 'dir' => 'dummy/events']),
            'categories' => categories_seeder(),
            'author_id' => Auth::id(),
            'promotion' => '0',
            'is_completed' => '0',
            'qr_code' => $request->url,
            'type' => $request->type,
            'start' => $request->start,
            'end' => $request->end,
        ];

        Event::create($array_job);

        Cache::store('database')->put('_' . Auth::id() . '_full_calendar_event', $events, Config::get('cache.lifetime'));

        return response()->json(['success' => 'Success']);
    }

    public function destroy()
    {
        # code...
    }
}
