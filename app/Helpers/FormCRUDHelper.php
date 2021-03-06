<?php
use App\Modules\Backend\Categories\Models\Category;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

if (!function_exists('form_json_convert')) {
    function form_json_convert($field, string $json_key)
    {
        if (is_array($field)) {
            $array = array();

            if ($json_key == 'categories_id') {
                $categories = Category::query()->whereIn('slug', $field)->get();

                foreach ($categories as $index => $value) {
                    array_push($array, $value->id);
                }
            }

            $result[$json_key] = $array;

            return $result;
        }

        return $field;
    }
}

if (!function_exists('blog_form_message')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function blog_form_message($type, $prefix)
    {
        $messages = [
            $prefix . '_title.min' => __('form.blog_title_min'),
            $prefix . '_title.max' => __('form.blog_title_max'),
            $prefix . '_title.required' => __('form.blog_title_required'),
            $prefix . '_title.string' => __('form.blog_title_string'),

            $prefix . '_description.required' => __('form.blog_description_required'),
        ];
        if (strtolower($type) == 'create') {
            if ($prefix == Config::get('app.fallback_locale')) {
                $messages[$prefix . '_title.unique'] = __('form.blog_title_unique');
                $messages['categories.required'] = __('form.blog_categories_required');
            }
        }

        return $messages;
    }
}

if (!function_exists('blog_data_cache')) {
    function blog_data_cache(array $request, $user_id)
    {
        $languages = DB::table('localization')->select('locale_name', 'locale_code')->get();

        $request_array = $request;

        unset($request_array['_token']);

        unset($request_array['background_image']);

        unset($request_array['background_image_file']);

        foreach ($request_array as $key => $value) {
            $results[$key] = form_json_convert($value, 'categories_id');
        }

        $results['author_id'] = $user_id;

        foreach ($languages as $key => $language) {
            $results[($language->locale_code) . '_slug'] = ($results[($language->locale_code) . '_title'] != null) ? Str::slug($results[($language->locale_code) . '_title'], '-') : null;
        }

        return $results;
    }
}

if (!function_exists('form_tags')) {
    function form_tags($json_categories)
    {
        $string = '';

        foreach ($json_categories as $key => $value) {
            foreach ($value as $index => $item) {
                $category = Category::find($item);

                $string .= $category->title . ',';
            }
        }

        $result = rtrim($string, ',');

        return $result;
    }
}

if (!function_exists('cache_push')) {
    function cache_push($cache, $model_replicate)
    {
        $cache->push($model_replicate);

        return $cache;
    }
}

if (!function_exists('remove_slug_and_convert_model_to_array')) {
    function remove_slug_and_convert_model_to_array($model_replicate, $languages)
    {
        $array = $model_replicate->toArray();

        foreach ($languages as $key => $value) {
            unset($array[$value->locale_code . '_slug']);
        }

        unset($array['id']);

        return $array;
    }
}

if (!function_exists('model_replicate')) {
    function model_replicate($model, $languages, Request $request)
    {
        $replicate = $model;

        foreach ($languages as $key => $value) {
            $replicate->{$value->locale_code . '_title'} = $request->{$value->locale_code . '_title'};
            $replicate->{$value->locale_code . '_slug'} = Str::slug($request->{$value->locale_code . '_title'}, '-');
            $replicate->{$value->locale_code . '_description'} = $request->{$value->locale_code . '_description'};
        }

        if (isset($request->categories)) {
            $replicate->categories = form_json_convert($request->categories, 'categories_id');
        }

        return $replicate;
    }
}
