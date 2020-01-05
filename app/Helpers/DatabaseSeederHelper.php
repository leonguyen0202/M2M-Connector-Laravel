<?php

use App\Modules\Backend\Categories\Models\Category;
use App\User;

if (!function_exists('categories_seeder')) {

    /**
     * @param
     * @return
     */
    function categories_seeder()
    {
        $categories = Category::all()->random(5);

        // $append = '';

        $array = array();

        foreach ($categories as $key => $value) {
            // $append .= '{"categories_id":"' . $value->id . '"},';
            array_push($array, $value->id);
        }

        // $rtrim = rtrim($append, ',');
        $rtrim["categories_id"] = $array ;
        return $rtrim;
        // return '[' . $rtrim . ']';
    }
}

if (!function_exists('participants_seeder')) {

    /**
     * @param
     * @return
     */
    function participants_seeder($random)
    {
        $users = User::all()->random($random);

        $append = '';

        foreach ($users as $key => $value) {
            $append .= '{"user_id":"' . $value->id . '"},';
        }

        $rtrim = rtrim($append, ',');
        // return $rtrim;
        return '[' . $rtrim . ']';
    }
}

if (!function_exists('random_image')) {
    /**
     * @param array
     * @return string image
     */
    function random_image($params = array())
    {
        if (isset($params['disk'])) {
            $disk = $params['disk'];
        }

        if (isset($params['dir'])) {
            $dir = $params['dir'];
        }

        if ($files = \Storage::disk($disk)->allFiles($dir)) {
            $path = $files[rand(0, count($files) - 1)];

            $image_name = explode("/", $path);
        }

        return $image_name[2];
    }
}
