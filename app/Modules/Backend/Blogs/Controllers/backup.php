<?php

class backup
{
    /**
     * Begin LoginController
     */

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
        $this->middleware('guest'); // Register Controller
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
     * End LoginController
     */

    /**
     * Begin RegisterController
     */

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255', 'unique:users'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => __('form.name_required'),
            'name.unique'   => __('form.name_unique'),
            'name.max'      => __('form.max'),
            
            'email.required'    => __('form.required'),
            'email.unique'      => __('form.unique'),
            'email.email'       => __('form.email'),
            'email.max'         => __('form.max'),

            'password.required' => __('form.required'),
            'password.min'      => __('form.min'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => '0',
            'verifyToken' => Str::random(60),
        ]);
    }
    /**
     * End RegisterController
     */
    
     /**
     * return redirect()->route('home.index'); -> RegistersUsers.php
     */

    public function FunctionName(Type $var = null)
    {
        /**
         * AuthenticatesUsers.php -> authenticated
         */
        $job = (new SubscribeJob($user))->delay(Carbon::now()->addSeconds(5));

        dispatch($job);

        Cache::store('database')->put('_' . $user->id . '_blog_view', 'table', Config::get('cache.lifetime'));

        return (url()->previous() != RouteServiceProvider::HOME) ? redirect()->to(url()->previous()) : redirect()->to(RouteServiceProvider::HOME);

        /**
         * AuthenticatesUsers.php -> logout
         */
        $json_column = array();

        $subscribe_table = Schema::getColumnListing('subscribes');

        foreach ($subscribe_table as $key => $value) {
            $type = DB::connection()->getDoctrineColumn('subscribes', $value)->getType()->getName();

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

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');

        /**
         * AuthenticatesUsers.php -> loggedOut
         */
        return (url()->previous() != RouteServiceProvider::HOME) ? redirect()->to(url()->previous()) : redirect()->to(RouteServiceProvider::HOME);
    }
}

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect(RouteServiceProvider::HOME);
        }

        $db = DB::table('localization')->get();

        for ($i=0; $i < count($db) ; $i++) { 
            $languages[($db[$i])->locale_code] = [($db[$i])->locale_name];
        }
        
        if (Session::has('applocale') and array_key_exists(Session::get('applocale'), $languages)) {
            App::setLocale(Session::get('applocale'));
        } else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            // setcookie(strtolower(env('APP_NAME')).'_language', Config::get('app.fallback_locale'), Config::get('session.lifetime'));
            App::setLocale(Config::get('app.fallback_locale'));
        }

        return $next($request);
    }
}