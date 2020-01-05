<?php

namespace App\Modules\Frontend\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Categories\Models\Category;
use App\Modules\Backend\Events\Models\Event;
use App\Modules\Backend\Subscribes\Models\Subscribe;
use App\Traits\BotmanTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Jobs\DatabaseCacheJob;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Config;
// use Google\Cloud\BigQuery\BigQueryClient;

class CategoryController extends Controller
{
    use BotmanTraits;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        # parent::__construct();
        $this->is_subscribed = false;
    }

    public function index(Request $request)
    {
        $count_category = Category::all()->count();

        if ($count_category >= 9) {
            $categories = Category::query()->paginate(9);
        } else {
            $categories = Category::all();
        }

        if ($request->ajax()) {
            $view = view('Category::categories_load_data')->with(['categories' => $categories])->render();

            if ($view == "") {
                return response()->json(['button' => render_load_more_button(__('frontend.no_more_category'))]);
            }

            return response()->json(['html' => $view]);
        };

        return view('Category::index')->with([
            'categories' => $categories,
        ]);
    }

    public function detail(Request $request, $slug)
    {
        $category = Category::query()->where('slug', $slug)->first();

        // $posts = Blog::query()->whereIn('id', $this->getPostArrayID($category) )->get();

        // dd($posts);

        if ($category == null) {
            return redirect()->back();
        }

        $post_id = array();

        if (Cache::get('_'. $category->slug . '_top_posts')) {
            $top_posts = Cache::get('_'. $category->slug . '_top_posts');
        } else {
            $top_posts = Blog::query()->orderBy('visits', 'DESC')->whereJsonContains('categories->categories_id', $category->id)->limit(2)->get();

            for ($i = 0; $i < count($top_posts); $i++) {
                array_push($post_id, $top_posts[$i]->id);
            }

            $job = (new DatabaseCacheJob($category))->delay(Carbon::now()->addSeconds(20));

            dispatch($job);
        }

        $posts = Blog::query()->whereJsonContains('categories->categories_id', $category->id)->whereNotIn('id', $post_id)->orderBy('created_at', 'DESC')->get();

        if (count($posts) > 9) {
            $posts = Blog::query()->whereJsonContains('categories->categories_id', $category->id)->whereNotIn('id', $post_id)->orderBy('created_at', 'DESC')->paginate(9);
        }

        if (Cache::get('_'. $category->slug . '_top_contributors')) {
            $contributors = Cache::get('_'. $category->slug . '_top_contributors');
        } else {
            $contributors = Blog::query()
            ->whereJsonContains('categories->categories_id', $category->id)->select(
                DB::raw('COUNT(*) as total'),
                'author_id'
            )->groupBy('author_id')
            ->orderBy('total', 'DESC')->limit(3)->get();

            $job = (new DatabaseCacheJob($category))->delay(Carbon::now()->addSeconds(20));

            dispatch($job);
        }

        if ($request->ajax()) {

            if ($posts instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $view = view('Category::load')->with(['posts' => $posts])->render();

                if ($view == "") {
                    return response()->json(['button' => render_load_more_button(__('frontend.no_more_post'))]);
                }

                return response()->json(['html' => $view]);
            } else {
                return response()->json(['button' => render_load_more_button(__('frontend.no_more_post'))]);
            }
        };

        if (Auth::user()) {
            $subscriber = Subscribe::query()->where([
                ['email', '=', Auth::user()->email]
            ])->first();

            if ($subscriber != null) {
                $this->is_subscribed = check_subscribe('_' . Auth::id() . '_categories', $category, 'categories');
            }
        }

        return view('Category::detail')->with([
            'category' => $category,
            'posts' => $posts,
            'top_posts' => $top_posts,
            'contributors' => $contributors,
            'is_subscribed' => $this->is_subscribed,
        ]);
    }
}
