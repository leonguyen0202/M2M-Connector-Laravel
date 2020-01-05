<?php

use App\Modules\Backend\Categories\Models\Category;
use App\Modules\Backend\Subscribes\Models\Subscribe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

if (!function_exists('split_sentence')) {
    function split_sentence($input, int $len, string $end)
    {
        $str = $input;
        if (strlen($input) > $len) {
            $str = explode("\n", wordwrap($input, $len));
            $str = $str[0] . $end;
        }

        return $str;
    }
}

if (!function_exists('render_all_categories')) {
    function render_all_categories($type, $model)
    {
        $category = Category::find($model);

        switch ($type) {
            case 'title':
                return $category->title;
                break;
            case 'slug':
                return $category->slug;
                break;
            default:
                return $category->hex_color;
                break;
        }
    }
}

if (!function_exists('render_load_more_button')) {
    function render_load_more_button($message)
    {
        return '<div class="col-md-3 mr-auto ml-auto">
                    <span class="btn btn-primary btn-round btn-block">' . $message . '</span>
                </div>';
    }
}

if (!function_exists('render_category_class')) {
    function render_category_class($type, $model)
    {
        // $model_json = json_decode($model->categories);

        $category = Category::find(($model->categories)['categories_id'][array_rand(($model->categories)['categories_id'])]);

        switch ($type) {
            case 'div':
                return '<div class="stats-link pull-right">
                            <a href="' . route('category.detail', $category->slug) . '" class="footer-link">
                                ' . $category->title . '
                            </a>
                        </div>';
                break;

            default:
                return '<h6 class="category">
                            <a href="' . route('category.detail', $category->slug) . '" class="text-primary">
                                ' . $category->title . '
                            </a>
                        </h6>';
                break;
        }

    }
}

if (!function_exists('render_categories')) {
    function render_categories($type, $model)
    {
        $model_json = json_decode($model->categories);

        $category = Category::find($model_json[array_rand($model_json)]->categories_id);

        switch ($type) {
            case 'title':
                return $category->title;
                break;
            case 'color':
                return $category->hex_color;
                break;
            default:
                # code...
                break;
        }
    }
}

if (!function_exists('slider_active_class')) {
    function slider_active_class($key, $class)
    {
        return ($key == 0) ? $class : '';
    }
}

if (!function_exists('render_conditional_class')) {
    function render_conditional_class($condition, $class, $sub_class)
    {
        return ($condition) ? $class : $sub_class;
    }
}

if (!function_exists('set_request_class')) {

    function set_request_class($path, $class)
    {
        return call_user_func_array('Request::is', (array) $path) ? $class : '';
    }
}

if (!function_exists('render_dashboard_breadcrumb')) {

    function render_dashboard_breadcrumb()
    {
        if (call_user_func_array('Request::is', ['dashboard'])) {
            return __('backend.dashboard');
        } else {
            $url_string = trim(str_replace(env('APP_URL'), '', url()->current()), '/');

            $url = explode("/", $url_string);

            if (count($url) < 3) {
                return "<a href='".route('dashboard.index')."' style='text-decoration:none;'>".__('backend.dashboard')."</a>&nbsp;/&nbsp;".ucfirst($url[1])."";
            } else {
                return "<a href='".route('dashboard.index')."' style='text-decoration:none;'>".__('backend.dashboard')."</a>&nbsp;/&nbsp;<a href='".route(Str::plural(strtolower($url[1])).'.index')."' style='text-decoration:none;'>".Str::singular(ucfirst($url[1]))."</a>&nbsp;/&nbsp;".ucfirst($url[2])."&nbsp;New&nbsp;".Str::singular(ucfirst($url[1]))."";
            }
        }
    }
}

if (!function_exists('set_strong_navigation_active')) {

    function set_strong_navigation_active($path, $word)
    {
        return call_user_func_array('Request::is', (array) $path) ? '<strong>' . $word . '</strong>' : $word;
    }
}

if (!function_exists('check_subscribe')) {
    function check_subscribe($cache_key, $model, $json_column_key)
    {
        if (Cache::get($cache_key)) {
            $cache_data = Cache::get($cache_key);

            foreach ($cache_data as $key => $value) {
                if ($value == $model->id) {
                    return true;
                }
            }
        } else {
            $check = Subscribe::query()->where([
                ['email', '=', Auth::user()->email],
            ])->whereJsonContains(($json_column_key) . '->' . ($json_column_key), $model->id)->first();

            if ($check) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('json_return')) {

    function json_return($column_json, $record, $json_key)
    {
        $result = array();

        if ($column_json == null) {
            $result[$json_key] = [$record->id];

            return $result;
        } else {
            $original = array();

            $duplicate = array();

            array_push($original, $record->id);

            foreach ($column_json[$json_key] as $key => $value) {
                array_push($original, $value);

                if ($value == $record->id) {
                    array_push($duplicate, $value);

                    // unset($column_json[$json_key][$key]);
                }
            }

            // $result[$json_key] = array_map("unserialize", array_unique(array_map("serialize", array_diff($original, $duplicate) )));

            $result[$json_key] = array_diff($original, $duplicate);

            if (count($result[$json_key]) == 0) {
                return null;
            }

            return $result;
        }
    }
}

if (!function_exists('array_flatten')) {
    function array_flatten($array, $return)
    {
        for ($x = 0; $x <= count($array); $x++) {
            if (is_array($array[$x])) {
                $return = array_flatten($array[$x], $return);
            } else {
                if (isset($array[$x])) {
                    $return[] = $array[$x];
                }
            }
        }
        return $return;
    }
}
