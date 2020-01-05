<?php

namespace App\Modules\Backend\Events\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
    }
    public function index()
    {
        return view('Events::index');
    }
}
