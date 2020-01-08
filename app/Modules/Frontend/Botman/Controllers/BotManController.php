<?php

namespace App\Modules\Frontend\Botman\Controllers;

use App\Http\Controllers\Controller;
/**
 * Botman Miscellaneous
 */
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\Drivers\Web\WebDriver;
use App\Traits\BotmanTraits;
use BotMan\BotMan\BotMan;

/**
 * Model Miscellaneous
 */
// use App\User;
use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Categories\Models\Category;
use App\Modules\Backend\Events\Models\Event;


// use App\Modules\Backend\Subscribes\Models\Subscribe;

/**
 * Laravel Miscellaneous
 */
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

// use Illuminate\Support\Facades\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\View;
// use JavaScript;

// use Google\Cloud\BigQuery\BigQueryClient;

class BotManController extends Controller
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
        $this->base_table = ['user', 'blog', 'event', 'category'];
        $this->locale = DB::table('localization')->get();
    }

    public function handle()
    {
        $botman = app('botman');

        $botman_button = array();

        for ($i=0; $i < count($this->locale); $i++) { 
            array_push($botman_button, Button::create( ($this->locale)[$i]->locale_name )->value( ($this->locale)[$i]->locale_code ) );
        }

        DriverManager::loadDriver(WebDriver::class);

        $question = Question::create(__('botman.language_question'))
        ->callbackId('yes')
        ->addButtons($botman_button);

        // $botman->ask($question, function (Answer $answer)
        // {
        //     $selectedValue = $answer->getText();

        //     $botman->reply($selectedValue);
        // });

        $botman->hears('{message}', function ($botman, $message) {

            if ( strtolower($message) == 'hi' || strtolower($message) == 'hello') {
                $botman->reply("Hello there");
                $botman->reply("What do you want me to do?");
            } else {
                $keyword = implode("-", preg_split('/[-\s,_"?%&]+/', $message));

                $botman->reply("<a href='" . env('APP_URL') . "/chatbot/filter/keyword=" . strtolower($keyword) . "&&language_code=en' target='_blank'>Result list</a>");
            }
        });

        $botman->listen();
    }

    public function filter($keyword, $language_code)
    {
        $string = trim(str_replace(env('APP_URL'), '', url()->previous()), '/');

        $url = explode("/", $string);

        if ($url[0] != 'botman') {
            return redirect()->route('home.index');
        }

        $blog_id = array();

        $search_keyword_array = explode("-", $keyword);

        $categories_records = Category::all();

        $category_keyword = array();

        for ($i=0; $i < count($search_keyword_array); $i++) { 
            
            foreach ($categories_records as $value) {

                $params['title'] = explode(" ", $value->title);

                for ($k = 0; $k < count($params['title']); $k++) {

                    if (strtolower($search_keyword_array[$i]) == strtolower($params['title'][$k])) {

                        array_push($category_keyword, $params['title'][$k]);

                    }
                }
            }

            if (is_numeric($search_keyword_array[$i])) {
                $params['limit'] = $search_keyword_array[$i];
            }

            $blog = Blog::query()->where([
                [(Cookie::get(strtolower(env('APP_NAME')) . '_language') . '_title'), '=', $search_keyword_array[$i]],
            ])->orWhere([
                [(Config::get('app.fallback_locale') . '_title'), '=', $search_keyword_array[$i]],
            ])->get();

            if (!$blog->isEmpty()) {
                foreach ($blog as $key => $value) {
                    array_push($blog_id, $value->id);
                }
            }
        }

        $categories = Category::query()->whereIn('title', $category_keyword)->get();

        foreach ($categories as $key => $value) {
            $blog = Blog::query()->whereJsonContains('categories->categories_id', $value->id)->get();

            foreach ($blog as $index => $item) {
                array_push($blog_id, $item->id);
            }
        }

        if (isset($params['limit'])) {
            $blog_results = Blog::query()->whereIn('id', array_unique($blog_id) )->limit($params['limit'])->get();
        } else {
            $blog_results = Blog::query()->whereIn('id', array_unique($blog_id))->get();
        }

        $results = collect([]);

        foreach ($blog_results as $key => $value) {
            $results->push($value);
        }

        return view('Botman::index')->with([
            'results' => $results
        ]);

        $search_keyword_array = explode("-", $keyword);

        $categories_records = Category::all();

        $category_keyword = array();

        $limit_array = array();

        for ($i = 0; $i < count($search_keyword_array); $i++) {

            $params = ['message' => $search_keyword_array[$i], 'string' => ['user', 'blog', 'event', 'post', 'category', 'tag'], 'string_type' => ['singular', 'plural']];

            // if ($this->message_singular_plural($search_keyword_array[$i], 'blog', 'plural') == true) {
            //     dd($search_keyword_array);
            // }

            if ($this->message_singular_plural($params)) {

                for ($t = 0; $t < count($this->base_table); $t++) {
                    if (strtolower($search_keyword_array[$i]) == strtolower(($this->base_table)[$t])) {
                        $this->table = Str::singular(ucfirst($search_keyword_array[$i]));
                    } elseif (Str::singular(strtolower($search_keyword_array[$i])) == 'post') {
                        $this->table = 'Blog';
                    } elseif (Str::singular(strtolower($search_keyword_array[$i])) == 'tag') {
                        $this->table = 'Category';
                    }
                }

            } else {
                $this->table = $this->base_table;
            }

            foreach ($categories_records as $value) {

                $params['title'] = explode(" ", $value->title);

                for ($k = 0; $k < count($params['title']); $k++) {

                    if (strtolower($search_keyword_array[$i]) == strtolower($params['title'][$k])) {

                        array_push($category_keyword, $params['title'][$k]);

                    }
                }
            }

            if (is_numeric($search_keyword_array[$i])) {
                array_push($limit_array, $search_keyword_array[$i]);
            }

            $params = ['message' => $search_keyword_array[$i], 'order_keyword' => ['top', 'popular', 'unpopular', 'less', 'least'], 'order_type' => ['ASC', 'DESC']];

        }

        if (!empty($category_keyword)) {

            if (count($category_keyword) > 1) {
                foreach ($categories_records as $value) {
                    $title = explode(" ", $value->title);

                    foreach ($title as $key => $value) {
                        foreach ($category_keyword as $category_key => $item) {
                            if (strtolower($value) == strtolower($item)) {
                                $category[$category_key] = Category::query()->where([
                                    ['title', 'like', '%' . $item . '%'],
                                ])->first();
                            }
                        }
                    }
                }
            } else {
                $category = Category::query()->where([
                    ['title', '=', $category_keyword[0]],
                ])->first();
            }



            if (is_array($category)) {
                $unique_category_id = array_unique($category);
    
                foreach ($unique_category_id as $key => $value) {
    
                    if (count($limit_array) > 1) {
                        # code...
                    }
                    $blog_array[$key] = Blog::query()->whereIn('id', $this->getPostArrayID($value))->get();
                }
    
                $blog_id = array();
    
                for ($i=0; $i < count($blog_array); $i++) { 
                    foreach ($blog_array[$i] as $item) {
                        array_push($blog_id, $item->id);
                    }
                }
    
                $blog = Blog::query()->whereIn('id', $blog_id)->get();
            } else {
                $blog = Blog::query()->whereIn('id', $this->getPostArrayID($category))->get();
            }

            $blog_as_title = Blog::query()->where([
                ['title', 'like', '%' . trim(implode(" ", $search_keyword_array)) . '%'],
            ])->get();


        } else {
            $blog = new Collection();
            
            $blog_as_title = Blog::query()->where([
                ['title', 'like', '%' . trim(implode(" ", $search_keyword_array)) . '%'],
            ])->get();

            $event_as_title = Event::query()->where([
                ['title', 'like', '%' . trim(implode(" ", $search_keyword_array)) . '%'],
            ])->get();
        }
        // $collection = new Collection();
        dd($blog);

        return view()->with([
            'blog' => $blog,
            'blog_as_title' => $blog_as_title,
            'event_as_title' => $event_as_title
        ]);

        // dd($this->table);

        if (is_array($this->table)) {

            for ($a = 0; $a < count($this->table); $a++) {
                ${Str::singular(strtolower(($this->table)[$a]))} = ($this->converting_model(Str::plural(ucfirst(($this->table)[$a])), Str::singular(ucfirst(($this->table)[$a]))))::query()->limit(5)->get();

                var_dump(${Str::singular(strtolower(($this->table)[$a]))});
            }
        } else {
            if ($this->table == 'Category') {
                $categories = Category::all();
            }
            echo $this->table;
        }

        die;
    }

    protected function message_order_keyword($params = array())
    {
        if (isset($params['message'])) {
            $this->message = $params['message'];
        }

        if (isset($params['order_keyword'])) {
            $this->order_keyword = $params['order_keyword'];
        }

        if (isset($params['order_type'])) {
            $this->order_type = $params['order_type'];
        }

        return 'DESC';
    }

    protected function message_singular_plural($params = array())
    {
        if (isset($params['message'])) {
            $this->message = $params['message'];
        }

        if (isset($params['string'])) {
            $this->string = $params['string'];
        }

        if (isset($params['string_type'])) {
            $this->string_type = $params['string_type'];
        }

        for ($i = 0; $i < count($this->string_type); $i++) {

            for ($s = 0; $s < count($this->string); $s++) {
                if ($this->message == Str::{($this->string_type)[$i]}(strtolower(($this->string)[$s]))) {
                    return true;
                }
            }

        }
        return false;
    }

    protected function converting_model($plural, $singular)
    {
        return "App\\Modules\\Backend\\" . $plural . "\\Models\\" . $singular;
    }
}
