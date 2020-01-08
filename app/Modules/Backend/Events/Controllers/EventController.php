<?php

namespace App\Modules\Backend\Events\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Backend\Events\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $this->languages = DB::table('localization')->select('locale_name', 'locale_code')->get();
    }

    /**
     * Render a listing of the events.
     *
     * @return \Illuminate\Http\Response
     */
    protected function render_event()
    {
        // if (Cache::has('_' . Auth::id() . '_full_calendar_event')) {
        //     $events = Cache::get('_' . Auth::id() . '_full_calendar_event');
        // } else {
            $events = array();

            $selectable_description = array();

            foreach ($this->languages as $key => $value) {
                array_push($selectable_description, $value->locale_code . '_title');
            }

            $data = Event::query()->where([
                ['author_id', '!=', Auth::id()],
                ['is_completed', '=', '0'],
                ['event_date', '>', Carbon::now()->toDateTimeString()],
            ])->orderBy('event_date', 'ASC')->get($selectable_description);

            foreach ($data as $key => $value) {
                array_push($events, [
                    "title" => $value->en_title,
                    "start" => Carbon::parse($value->event_date)->toDateString(),
                    "className" => 'event-green',
                    "editable" => false,
                ]);
            }

            $data = Auth::user()->has_events;

            foreach ($data as $key => $value) {
                array_push($events, [
                    "title" => $value->en_title,
                    "start" => Carbon::parse($value->event_date)->toDateString(),
                    "className" => 'event-azure',
                ]);
            }
        // }

        return response()->json($events);
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
}
