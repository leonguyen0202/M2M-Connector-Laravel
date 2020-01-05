<?php

namespace App\Modules\Backend\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        return view('Dashboard::index');
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
