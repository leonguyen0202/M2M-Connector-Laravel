<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Modules\Backend\Events\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\SubscribeJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get view email field name
     *
     * @return string
     */
    protected function email_view_name()
    {
        return 'login_email';
    }

    /**
     * Get view password field name
     *
     * @return string
     */
    protected function password_view_name()
    {
        return 'login_password';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->email_view_name() => 'required|string',
            $this->password_view_name() => 'required|string',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return ['email' => $request->{$this->email_view_name()}, 'password' => $request->{$this->password_view_name()}, 'is_active' => '1'];
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $job = (new SubscribeJob($user))->delay(Carbon::now()->addSeconds(5));

        dispatch($job);

        Cache::store('database')->put('_' . $user->id . '_blog_view', 'table', Config::get('cache.lifetime'));

        $auth_blogs = Auth::user()->has_blogs;
        
        $posts = collect([]);

        foreach ($auth_blogs as $key => $value) {
            $posts->push($value);
        }

        Cache::store('database')->put('_' . $user->id . '_blog_data', $posts, Config::get('cache.lifetime'));

        $languages = DB::table('localization')->select('locale_name', 'locale_code')->get();

        $events = array();

        $selectable_description = array();

        foreach ($languages as $key => $value) {
            array_push($selectable_description, $value->locale_code . '_title');
        }

        array_push($selectable_description, 'start');
        array_push($selectable_description, 'end');

        $data = Event::query()->where([
            ['author_id', '!=', $user->id],
            ['type', '=', 'event'],
            ['is_completed', '=', '0'],
            ['start', '>', Carbon::now()->toDateTimeString()],
        ])->orderBy('start', 'ASC')->get($selectable_description);

        foreach ($data as $key => $value) {
            array_push($events, [
                "title" => $value->{Config::get('app.fallback_locale').'_title'},
                "start" => Carbon::parse($value->start)->toDateTimeString(),
                "end" => Carbon::parse($value->end)->toDateTimeString(),
                "className" => 'event-green',
                "editable" => false,
            ]);
        }

        $data = $user->has_events;

        foreach ($data as $key => $value) {

            if ($value->type == 'member') {
                $className = 'event-orange';
            } else {
                $className = 'event-azure';
            };

            array_push($events, [
                "title" => $value->{Config::get('app.fallback_locale').'_title'},
                "start" => Carbon::parse($value->start)->toDateString(),
                "end" => Carbon::parse($value->end)->toDateString(),
                "className" => $className,
            ]);
        }

        Cache::store('database')->put('_' . $user->id . '_full_calendar_event', $events, Config::get('cache.lifetime'));

        return (url()->previous() != RouteServiceProvider::HOME) ? redirect()->to(url()->previous()) : redirect()->to(RouteServiceProvider::HOME);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $json_column = array();

        $subscribe_table = Schema::getColumnListing('subscribes');

        foreach ($subscribe_table as $key => $value) {
            $type = \Illuminate\Support\Facades\DB::connection()->getDoctrineColumn('subscribes', $value)->getType()->getName();

            if ($type == 'json') {
                array_push($json_column, $value);
            }
        }

        foreach ($json_column as $key => $value) {
            if (Cache::has('_' . Auth::id() . '_' . $value)) {
                Cache::forget('_' . Auth::id() . '_' . $value);
            }
        }

        Cache::forget('_' . Auth::id() . '_blog_view');

        Cache::forget('_' . Auth::id() . '_blog_data');

        Cache::forget('_' . Auth::id() . '_full_calendar_event');

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return (url()->previous() != RouteServiceProvider::HOME) ? redirect()->to(url()->previous()) : redirect()->to(RouteServiceProvider::HOME);
    }
}
