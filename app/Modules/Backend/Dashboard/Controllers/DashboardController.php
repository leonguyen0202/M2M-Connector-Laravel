<?php

namespace App\Modules\Backend\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Backend\Subscribes\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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
    public function minimizeSidebar()
    {
        if (Cache::has('_' . Auth::id() . '_sidebar_mini')) {
            Cache::forget('_' . Auth::id() . '_sidebar_mini');
        } else {
            Cache::store('database')->put('_' . Auth::id() . '_sidebar_mini', 'sidebar-mini', Config::get('cache.lifetime'));
        }
        return response()->json(['sidebar_mini' => 'Set success!']);
    }
    public function index()
    {
        $follower = Subscribe::query()->whereJsonContains('users->users', Auth::id())
            ->select(
                DB::raw('COUNT(*) as total'),
            )->first();

        return view('Dashboard::index')->with([
            'follower' => $follower->total,
        ]);
    }

    protected function validation($id)
    {
        // $team = Team::find($id);

        // if ($team == null || $team->leader != Auth::id()) {
        //     return 'Data not found. Please try again!';
        // }

        // $member = Admin::find($id);

        // if ($member == null) {
        //     return 'Data not found. Please try again!';
        // }

        // return $member;
    }

    public function check_slug(Request $request)
    {
        // if ($request->input('copyright_name') != null) {
        //     # belong to copyright
        //     # code...
        //     $slug = SlugService::createSlug(Copyright::class, 'slug', $request->input('copyright_name'));
        // } else if ($request->input('video_name') != null) {
        //     # coode...
        //     $slug = SlugService::createSlug(Video::class, 'slug', $request->video_name);
        // } else if ($request->input('group_name') != null) {
        //     # code...
        //     $slug = SlugService::createSlug(Group::class, 'slug', $request->input('group_name'));
        // }
        // // $slug = SlugService::createSlug(TranslateGroup::class, 'slug', $request->name);

        // return response()->json(['slug' => $slug]);
        // return response()->json(['slug' => $slug]);
    }
}
