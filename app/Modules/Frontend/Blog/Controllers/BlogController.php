<?php

namespace App\Modules\Frontend\Blog\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Categories\Models\Category;
use App\Modules\Backend\Events\Models\Event;
use App\Modules\Backend\Subscribes\Models\Subscribe;
use App\Traits\BotmanTraits;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Arr;
// use JavaScript;

use Illuminate\Support\Facades\DB;

// use Google\Cloud\BigQuery\BigQueryClient;

class BlogController extends Controller
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
        $this->is_followed = false;
        $this->is_bookmarked = false;
    }

    public function index(Request $request)
    {
        $post_array_id = array();

        $top_posts = Blog::query()->orderBy('visits', 'DESC')->limit(4)->get();

        if (!$top_posts->isEmpty()) {
            for ($i = 0; $i < count($top_posts); $i++) {
                array_push($post_array_id, $top_posts[$i]->id);
            }
        }

        $posts = Blog::query()->whereNotIn('id', $post_array_id)->orderBy('created_at', 'DESC')->get();

        $events = Event::query()->orderBy('created_at', 'DESC')->limit(5)->get();

        $contributors = Blog::query()->select(
            DB::raw('COUNT(*) as total'),
            'author_id'
        )->groupBy('author_id')->orderBy('total', 'DESC')->limit(3)->get();

        if ($request->ajax()) {
            $view = view('Blog::load')->with(['posts' => $posts])->render();

            if ($view == "") {
                return response()->json(['button' => render_load_more_button(__('frontend.no_more_post'))]);
            }

            return response()->json(['html' => $view]);
        };

        return view('Blog::index')->with([
            'contributors' => $contributors,
            'posts' => $posts,
            'top_posts' => $top_posts,
        ]);
    }

    public function detail($slug)
    {
        $blog = Blog::query()->where(Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_slug', $slug)->first();

        if ($blog == null) {
            $blog = Blog::query()->where(Config::get('app.fallback_locale') . '_slug', $slug)->first();
        }

        if ($blog == null) {
            return redirect()->route('home.index');
        }

        // $explode = preg_split('/[-\s\s,;:<>\/_"?%&]+/', $blog->en_description);

        $category = Category::find(($blog->categories)['categories_id'][array_rand(($blog->categories)['categories_id'])]);

        $similar_stories = Blog::query()->whereJsonContains('categories->categories_id', $category->id)->whereNotIn('id', [$blog->id])->inRandomOrder()->limit(2)->get();

        $user = User::find($blog->author_id);

        if (Auth::user()) {
            $subscriber = Subscribe::query()->where([
                ['email', '=', Auth::user()->email],
            ])->first();

            if ($subscriber != null) {
                $this->is_followed = check_subscribe('_' . Auth::id() . '_users', $user, 'users');
                $this->is_bookmarked = check_subscribe('_' . Auth::id() . '_blogs', $blog, 'blogs');
            }
        }

        return view('Blog::detail')->with([
            'blog' => $blog,
            'similar_stories' => $similar_stories,
            'is_followed' => $this->is_followed,
            'is_bookmarked' => $this->is_bookmarked,
        ]);
    }
}
