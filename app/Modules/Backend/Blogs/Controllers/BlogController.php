<?php

namespace App\Modules\Backend\Blogs\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Backend\Blogs\Jobs\BlogCRUDJob;
use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Blogs\Requests\BlogFormRequestRules;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

// use Illuminate\Http\File;
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
        $this->data_slug = null;
        $this->edit_mode = false;
        $this->fileName = 'default-background.jpg';
        $this->name = str_shuffle(Str::random(60)) . '_' . time();
        $this->categories = Category::all();
        $this->languages = DB::table('localization')->select('locale_name', 'locale_code')->get();
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
            ->addColumn('title', function ($post) {
                // Get Cookie first
                if ($post->{Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_title'} != null) {
                    return split_sentence($post->{Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_title'}, 30, '...');
                } else {
                    return split_sentence($post->{Config::get('app.fallback_locale') . '_title'}, 30, '...');
                }
            })
            ->addColumn('categories', function ($post) {
                return '<span class="badge badge-info">' . count(($post->categories)['categories_id']) . '</span>';
            })
            ->addColumn('comments', function ($post) {
                return '<span class="badge badge-warning">0</span>';
            })
            ->editColumn('action', function ($post) {
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
                                data-placement='top' data-slug='" . $slug . "' title='View Post'>
                                <i class='now-ui-icons ui-1_zoom-bold'></i>
                            </a>
                            &nbsp;
                            <a href='" . route('blogs.edit', $slug) . "' class='btn btn-primary btn-fab btn-icon btn-round blog-edit'
                                data-toggle='tooltip' data-placement='top' title='Edit Post'>
                                <i class='now-ui-icons ui-2_settings-90'></i>
                            </a>
                            &nbsp;
                            <a href='#' class='btn btn-danger btn-fab btn-icon btn-round blog-delete'
                                data-toggle='tooltip' data-slug='" . $slug . "' data-placement='top' title='Delete Post'>
                                <i class='now-ui-icons ui-1_simple-remove'></i>
                            </a>
                        </div>";
            })
            ->editColumn('background', function ($post) {
                if ($post->{Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'} != null) {
                    $slug = $post->{Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'};
                } else {
                    $slug = $post->{Config::get('app.fallback_locale') . '_slug'};
                }

                if ($post instanceof \App\Modules\Backend\Blogs\Models\Blog) {
                    if (!file_exists($post->media_url('thumb'))) {
                        return "<img src='" . $post->getFirstMediaUrl('blog-images') . "' alt='" . $slug . "' style='width:50px;height:33px'>";
                    }

                    return "<img src='" . $post->media_url('thumb') . "' alt='" . $slug . "'>";
                }
                return "<img src='" . asset('storage/images/upload/' . $post->background_image) . "' alt='" . $slug . "' style='width:50px;height:33px'>";

            })
            ->rawColumns(['background', 'categories', 'comments', 'action'])
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

    public function check_duplicate($title)
    {
        return response()->json(['status' => 200]);
    }

    /**
     * Getting tinyMCE text area content
     *
     * @param string $view
     * @return \Illuminate\Http\Response
     */
    public function ajax_tinyMCE_description(Request $request)
    {
        if (!FacadeRequest::isMethod("GET")) {
            return response()->json(['error' => __('form.not_support_method')]);
        }

        $slug = $request->slug;

        $selectable_description = array();

        foreach ($this->languages as $key => $value) {
            array_push($selectable_description, $value->locale_code . '_description');
        }

        $result = Blog::query()->where([
            ['author_id', '=', Auth::id()],
        ])->where([
            [(Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'), '=', $slug],
        ])->orWhere([
            [(Config::get('app.fallback_locale') . '_slug'), '=', $slug],
        ])->select($selectable_description)->first();

        if ($result == null) {
            return response()->json(['error' => __('form.data_not_exist')]);
        }

        $data = $result->toArray();

        foreach ($data as $key => $value) {
            if ($value != null) {
                $data[$key] = html_entity_decode( htmlspecialchars( str_replace(' />', '>', $value) ) );
            }
            
        }

        return response()->json(['data' => (object) $data]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * Begin replicate Cache after debug
         */
        // Cache::store()->put('_' . Auth::id() . '_blog_data', Auth::user()->has_blogs, Config::get('cache.lifetime'));
        /**
         * End replicate Cache after debug
         */
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
        return view('Blogs::mode')->with([
            'languages' => $this->languages,
            'categories' => $this->categories,
            'edit_mode' => $this->edit_mode,
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

        $form_request = new BlogFormRequestRules();

        $validator = Validator::make($request->all(), $form_request->rules('create', $this->config_locale), blog_form_message('create', $this->config_locale));

        if ($validator->fails()) {
            return Redirect::back()->with('errors', $validator->errors()->all());
        }

        foreach ($this->languages as $key => $value) {
            if ($value->locale_code != $this->config_locale) {
                if ($request->{($value->locale_code) . '_title'} != null || $request->{($value->locale_code) . '_description'} != null) {
                    $validator = Validator::make($request->all(), $form_request->rules('create', $value->locale_code), blog_form_message('create', $value->locale_code));
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
                'background_image_file' => ['image', 'mimetypes:image/jpg, image/jpeg, image/png', 'max:3072'],
            ], [
                'background_image_file.image' => 'Upload file need to be image',
                'background_image_file.mimetypes' => 'We only accept JPEG, JPG or PNG',
                'background_image_file.max' => 'Your file has exceed :max bytes',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->with('errors', $validator->errors()->all());
            }

            $files = $request->file('background_image_file');

            $this->fileName = $this->name . '.' . $files->getClientOriginalExtension();

            Storage::disk('upload')->putFileAs('', $files, $this->fileName);
        }

        $array_object = blog_data_cache($request->all(), Auth::id());

        $array_object['background_image'] = $this->fileName;

        $job = (new BlogCRUDJob(null, $array_object, FacadeRequest::method()))->delay(Carbon::now()->addSeconds(10));

        dispatch($job);

        $cache_collection = collect([]);

        $posts = Auth::user()->has_blogs;

        if (Cache::has('_' . Auth::id() . '_blog_data')) {
            Cache::forget('_' . Auth::id() . '_blog_data');
        }

        foreach ($posts as $key => $value) {
            $cache_collection->push($value);
        }

        $cache_collection->push(
            (object) $array_object
        );

        Cache::store('database')->put('_' . Auth::id() . '_blog_data', $cache_collection, Config::get('cache.lifetime'));

        return redirect()->route('blogs.index')->with('success', ['Blog created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
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
            ['author_id', '=', Auth::id()],
        ])
            ->where([
                [(Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'), '=', $slug],
            ])->orWhere([
            [(Config::get('app.fallback_locale') . '_slug'), '=', $slug],
        ])->first();

        if ($post == null) {
            return redirect()->route('blogs.index')->with('error', [__('form.data_not_exist')]);
        }

        $this->edit_mode = true;

        return view('Blogs::mode')->with([
            'post' => $post,
            'languages' => $this->languages,
            'categories' => $this->categories,
            'edit_mode' => $this->edit_mode,
            'slug' => $slug,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        /**
         * Begin debug
         */

        /**
         * End debug
         */
        if (FacadeRequest::ajax() or !$request->isMethod('put')) {
            return Redirect::back()->with('errors', [__('form.not_support_method')]);
        }

        $post = Blog::query()->where([
            ['author_id', '=', Auth::id()],
        ])
            ->where([
                [(Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'), '=', $slug],
            ])->orWhere([
            [(Config::get('app.fallback_locale') . '_slug'), '=', $slug],
        ])->first();

        if ($post == null) {
            return Redirect::back()->with('errors', [__('form.data_not_exist')]);
        }

        $form_request = new BlogFormRequestRules();

        $validator = Validator::make($request->all(), $form_request->rules('update', $this->config_locale), blog_form_message('update', $this->config_locale));

        if ($validator->fails()) {
            return Redirect::back()->with('errors', $validator->errors()->all());
        }

        foreach ($this->languages as $key => $value) {
            if ($value->locale_code != $this->config_locale) {
                if ($request->{($value->locale_code) . '_title'} != null || $request->{($value->locale_code) . '_description'} != null) {
                    $validator = Validator::make($request->all(), $form_request->rules('update', $value->locale_code), blog_form_message('update', $value->locale_code));
                }
            }
        }

        if (empty($validator)) {
            return Redirect::back()->with('errors', [__('form.not_support_language')]);
        }

        if ($validator->fails()) {
            return Redirect::back()->with('errors', $validator->errors()->all());
        }

        foreach ($this->languages as $key => $value) {
            if ($request->{($value->locale_code) . '_title'} != $post->{($value->locale_code) . '_title'}) {
                $check_blog_title = Blog::query()->where([
                    [$value->locale_code . '_title', '=', $request->{($value->locale_code) . '_title'}],
                ])->first();

                if ($check_blog_title) {
                    return Redirect::back()->with('errors', [__('form.blog_title_unique')]);
                }
            }
        }

        $cache = $this->remove_cache($slug);

        if (isset($request->background_image_file)) {
            Validator::make([$request->background_image_file], [
                'background_image_file' => ['image', 'mimetypes:image/jpg, image/jpeg, image/png', 'max:3072'],
            ], [
                'background_image_file.image' => 'Upload file need to be image',
                'background_image_file.mimetypes' => 'We only accept JPEG, JPG or PNG',
                'background_image_file.max' => 'Your file has exceed :max bytes',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->with('errors', $validator->errors()->all());
            }

            $array_object = blog_data_cache($request->all(), Auth::id());

            $files = $request->file('background_image_file');

            $this->fileName = $this->name . '.' . $files->getClientOriginalExtension();

            Storage::disk('upload')->putFileAs('', $files, $this->fileName);

            $array_object['background_image'] = $this->fileName;

            if (!isset($request->categories) || !isset($array_object['categories'])) {
                $array_object['categories'] = $post->categories;
            }

            $cache->push(
                (object) $array_object
            );

        } else {
            $cache = cache_push($cache, model_replicate($post, $this->languages, $request) );

            $array_object = remove_slug_and_convert_model_to_array( model_replicate($post, $this->languages, $request), $this->languages );
        }

        $job = (new BlogCRUDJob($post->id, $array_object, FacadeRequest::method()))->delay(Carbon::now()->addSeconds(30));

        dispatch($job);

        if (Cache::has('_' . Auth::id() . '_blog_data')) {
            Cache::forget('_' . Auth::id() . '_blog_data');
        }

        Cache::store()->put('_' . Auth::id() . '_blog_data', $cache, Config::get('cache.lifetime'));

        return redirect()->route('blogs.index')->with('success', ['Blog updated successfully']);
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
            ['author_id', '=', Auth::id()],
        ])->where([
            [(Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug'), '=', $slug],
        ])->orWhere([
            [(Config::get('app.fallback_locale') . '_slug'), '=', $slug],
        ])->first();

        if ($post == null) {
            return response()->json(['error' => __('form.data_not_exist')]);
        }

        $cache = $this->remove_cache($slug);

        if (!($post->getMedia('blog-images'))->isEmpty()) {
            $post->clearMediaCollection('blog-images');
        }

        $post->delete();

        return response()->json(['success' => 'Data is deleted successfully!']);

        $job = (new BlogCRUDJob($post->id, null, FacadeRequest::method()))->delay(Carbon::now()->addSeconds(rand(5,20)));

        dispatch($job);
    }

    protected function remove_cache($slug)
    {
        $cache = Cache::get('_' . Auth::id() . '_blog_data');

        foreach ($this->languages as $key => $value) {
            foreach ($cache as $index => $item) {
                if ($item->{$value->locale_code . '_slug'} == $slug) {
                    $cache->forget($index);
                }
            }
        }

        return $cache;
    }
}
