<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
