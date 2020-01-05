<?php

namespace App\Modules\Backend\Blogs\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\BlogCRUDJob;
use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Blogs\Requests\BlogCreateFormRequest;
use App\Modules\Backend\Categories\Models\Category;
use App\Modules\Backend\Settings\Models\Developer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Http\File;
use Yajra\DataTables\DataTables;

// use JavaScript;

class BlogController extends Controller
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
        $this->is_table_view = true;
        $this->posts = null;
        $this->edit_mode = false;
        $this->config_locale = Config::get('app.fallback_locale');
        $this->upload_path = Developer::query()->where([['type', '=', 'upload']])->first();
    }

    /**
     * Rendering dataTable for table view
     * 
     * @return \Yajra\DataTables\DataTables
     */
    public function table()
    {
        if (!FacadeRequest::isMethod('POST')) {
            return response()->json(['error' => __('form.not_support_method')]);
        }

        if (Cache::has('_' . Auth::id() . '_blog_data')) {
            $posts = Cache::get('_' . Auth::id() . '_blog_data');
        } else {
            $posts = Auth::user()->has_blogs;
        }

        return DataTables::of($posts)
            ->addColumn('title', function (Blog $post) {
                // Get Cookie first
                if ($post->{Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_title'} != null) {
                    return split_sentence($post->{Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_title'}, 30, '...');
                } else {
                    return split_sentence($post->{Config::get('app.fallback_locale') . '_title'}, 30, '...');
                }
            })
            ->addColumn('categories', function (Blog $post) {
                return '<span class="badge badge-info">' . count(($post->categories)['categories_id']) . '</span>';
            })
            ->addColumn('comments', function (Blog $post) {
                return '<span class="badge badge-warning">0</span>';
            })
            ->editColumn('action', function (Blog $post) {
                if ($post->{Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'} != null) {
                    $slug = $post->{Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'};
                } else {
                    $slug = $post->{Config::get('app.fallback_locale') . '_slug'};
                }
                return "<div class='parent'>
                            <input type='hidden' class='token' id='token' name='token' value='" . $slug . "'>
                            <a href='#' class='btn btn-success btn-fab btn-icon btn-round blog-comments' data-toggle='tooltip'
                                data-placement='top' title='View Comment'>
                                <i class='now-ui-icons ui-2_chat-round'></i>
                            </a>
                            &nbsp;
                            <a href='#' class='btn btn-info btn-fab btn-icon btn-round blog-view' data-toggle='tooltip'
                                data-placement='top' title='View Post'>
                                <i class='now-ui-icons ui-1_zoom-bold'></i>
                            </a>
                            &nbsp;
                            <a href='".route('blogs.edit', $slug)."' class='btn btn-primary btn-fab btn-icon btn-round blog-edit'
                                data-toggle='tooltip' data-placement='top' title='Edit Post'>
                                <i class='now-ui-icons ui-2_settings-90'></i>
                            </a>
                            &nbsp;
                            <a href='#' class='btn btn-danger btn-fab btn-icon btn-round blog-delete'
                                data-toggle='tooltip' data-placement='top' title='Delete Post'>
                                <i class='now-ui-icons ui-1_simple-remove'></i>
                            </a>
                        </div>";
            })
            ->editColumn('background', function (Blog $post) {
                if ($post->{Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'} != null) {
                    $slug = $post->{Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'};
                } else {
                    $slug = $post->{Config::get('app.fallback_locale') . '_slug'};
                }
                if (!file_exists($post->media_url('thumb'))) {
                    return "<img src='".$post->getFirstMediaUrl('blog-images')."' alt='".$slug."' style='width:50px;height:33px'>";   
                }
                
                return "<img src='". $post->media_url('thumb') ."' alt='".$slug."'>";
            })
            ->rawColumns(['background','categories', 'comments', 'action'])
            ->make(true);
    }

    /**
     * Caching view for blog index
     * 
     * @param string $view
     * @return \Illuminate\Http\Response
     */
    public function switchView($view)
    {
        Session::put('blogview', $view);

        if (Cache::has('_' . Auth::id() . '_blog_view')) {
            Cache::forget('_' . Auth::id() . '_blog_view');
        }

        Cache::store('database')->put('_' . Auth::id() . '_blog_view', $view, Config::get('cache.lifetime'));

        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Cache::forget('_' . Auth::id() . '_blog_data');
        
        $collection = collect([]);

        $posts = Auth::user()->has_blogs;

        foreach ($posts as $key => $value) {
            $collection->push($value);
        }

        Cache::store('database')->put('_' . Auth::id() . '_blog_data', $collection, Config::get('cache.lifetime'));

        if (Cache::has('_' . Auth::id() . '_blog_view')) {
            $blog_view = Cache::get('_' . Auth::id() . '_blog_view');

            if ($blog_view != 'table') {
                $this->is_table_view = false;
            }
        }

        if (Cache::has('_' . Auth::id() . '_blog_data')) {
            $this->posts = Cache::get('_' . Auth::id() . '_blog_data');
        } else {
            $this->posts = Auth::user()->has_blogs;
        }

        return view('Blogs::index')->with([
            'is_table_view' => $this->is_table_view,
            'posts' => $this->posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $view = array();
        
        $languages = DB::table('localization')->select('locale_code', 'locale_name')->get();

        $categories = Category::all();

        foreach ($languages as $key => $value) {
            array_push($view, strtolower($value->locale_name));
        }

        return view('Blogs::mode')->with([
            'languages' => $languages,
            'categories' => $categories,
            'edit_mode' => $this->edit_mode
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if (FacadeRequest::ajax() or !$request->isMethod('post')) {
            return Redirect::back()->with('errors', [__('form.not_support_method')]);
        }

        $form_request = new BlogCreateFormRequest();

        $validator = Validator::make($request->all(), $form_request->rules($this->config_locale), blog_form_message($this->config_locale));

        if ($validator->fails()) {
            return Redirect::back()->with('errors', $validator->errors()->all());
        }

        $languages = DB::table('localization')->select('locale_code')->get();

        foreach ($languages as $key => $value) {
            if ($value->locale_code != $this->config_locale) {
                if ($request->{($value->locale_code) . '_title'} != null || $request->{($value->locale_code) . '_description'} != null) {
                    $validator = Validator::make($request->all(), $form_request->rules($value->locale_code), blog_form_message($value->locale_code));
                }
            }
        }

        if (empty($validator)) {
            return Redirect::back()->with('errors', [__('form.not_support_language')]);
        }

        if ($validator->fails()) {
            return Redirect::back()->with('errors', $validator->errors()->all());
        }

        if ($request->hasFile('background_image_file')) {

            Validator::make([$request->background_image_file], [
                'background_image_file' => ['image', 'mimetypes:image/jpg, image/jpeg, image/png', 'max:3072']
            ], [
                'background_image_file.image' => 'Upload file need to be image',
                'background_image_file.mimetypes' => 'We only accept JPEG, JPG or PNG',
                'background_image_file.max' => 'Your file has exceed :max bytes'
            ]);

            if ($validator->fails()) {
                return Redirect::back()->with('errors', $validator->errors()->all());
            }

            $files = $request->file('background_image_file');

            $name = str_shuffle(Str::random(60)) . '_' . time();

            $fileName = $name . '.' . $files->getClientOriginalExtension();

            Storage::disk('upload')->putFileAs('', $files, $fileName);

            // $request->background_image_file->move('images/upload/posts/', $fileName);
        } else {
            $fileName = 'default-background.jpg';
        }

        $array_object = blog_data_cache($request->all(), Auth::id());

        $array_object['background_image'] = $fileName;

        foreach ($languages as $key => $language) {
            $array_object[($language->locale_code) . '_slug'] = ($array_object[($language->locale_code) . '_title'] != null) ? Str::slug($array_object[($language->locale_code) . '_title'], '-') : null;
        }

        $blog = Blog::create($array_object);

        if ($request->hasFile('background_image_file')) {
            # code... addMediaFromRequest
            $item = Blog::find($blog->id);

            // $item->add_media_from_disk($name, $fileName);

            $item->addMedia($files)->usingName($name)->usingFileName($item->background_image)->toMediaCollection('blog-images');

            // $item->addMediaFromDisk($item->background_image, 'upload')->usingName($name)->usingFileName($item->background_image)->toMediaCollection('blog-images');
        }

        $posts = collect([]);
        
        $cache = Auth::user()->has_blogs;

        if (Cache::has('_' . Auth::id() . '_blog_data')) {
            Cache::forget('_' . Auth::id() . '_blog_data');
        }

        foreach ($cache as $key => $value) {
            $posts->push($value);
        }
        
        Cache::store('database')->put('_' . Auth::id() . '_blog_data', $posts, Config::get('cache.lifetime'));

        return redirect()->route('blogs.index')->with('success', ['Blog created successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $post = Blog::query()->where([
            [(Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'), '=', $slug],
        ])->first();

        if ($post == null) {
            $post = Blog::query()->where([
                [(Config::get('app.fallback_locale') . '_slug'), '=', $slug],
            ])->first();
        }

        if ($post == null || $post->author_id != Auth::id()) {
            return redirect()->route('blogs.index')->with('error', ['Data is not existed']);
        }

        $languages = DB::table('localization')->select('locale_code', 'locale_name')->get();

        $categories = Category::all();

        $this->edit_mode = true;

        return view('Blogs::mode')->with([
            'post' => $post,
            'languages' => $languages,
            'categories' => $categories,
            'edit_mode' => $this->edit_mode
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        if (!FacadeRequest::isMethod("DELETE")) {
            return response()->json(['error' => __('form.not_support_method')]);
        }

        $post = Blog::query()->where([
            [(Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'), '=', $slug],
        ])->first();

        if ($post == null) {
            $post = Blog::query()->where([
                [(Config::get('app.fallback_locale') . '_slug'), '=', $slug],
            ])->first();
        }

        if ($post == null || $post->author_id != Auth::id()) {
            return response()->json(['error' => 'Data is not exist']);
        }

        try {
            $post->getMedia('blog-images');
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No images']);
        }

        if (!($post->getMedia('blog-images'))->isEmpty()) {
            $post->clearMediaCollection('blog-images');
        }

        Cache::forget('_' . Auth::id() . '_blog_data');

        $auth_blogs = Auth::user()->has_blogs;

        $posts = collect([]);

        foreach ($auth_blogs as $key => $value) {
            $posts->push($value);
        }

        Cache::store()->put('_' . Auth::id() . '_blog_data', $posts, Config::get('cache.lifetime'));

        $post->delete();

        return response()->json(['success' => 'Data is deleted successfully!']);
    }
}
