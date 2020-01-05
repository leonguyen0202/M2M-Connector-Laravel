<?php
use Illuminate\Support\Facades\Config;
use App\Modules\Backend\Categories\Models\Category;

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
    function blog_form_message($prefix)
    {
        $messages = [
            $prefix . '_title.min' => __('form.blog_title_min'),
            $prefix . '_title.max' => __('form.blog_title_max'),
            $prefix . '_title.required' => __('form.blog_title_required'),
            $prefix . '_title.string' => __('form.blog_title_string'),
            $prefix . '_title.unique' => __('form.blog_title_unique'),

            $prefix . '_description.required' => __('form.blog_description_required'),
        ];

        if ($prefix == Config::get('app.fallback_locale')) {
            $messages['categories.required'] = __('form.blog_categories_required');

            return $messages;
        }
        
        return $messages;
    }
}

if (!function_exists('blog_data_cache')) {
    function blog_data_cache(array $request, $user_id)
    {
        $result = $request;

        unset($result['_token']);

        unset($result['background_image']);

        unset($result['background_image_file']);

        foreach ($result as $key => $value) {
            $array_object[$key] = form_json_convert($value, 'categories_id');
        }

        $array_object['author_id'] = $user_id;

        return $array_object;
    }
}
