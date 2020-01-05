<?php

namespace App\Traits;

use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Events\Models\Event;
/**
 *
 */
trait BotmanTraits
{
    public function getPostArrayID($category)
    {
        $posts = Blog::all();

        $array_id = array();

        // Blog::query()->chunk(100, function ($posts) use ($category, $array_id) {
            
        // });

        foreach ($posts as $key => $post) {
            $tags = json_decode($post->categories);

            for ($i=0; $i < count($tags); $i++) { 
                if ($tags[$i]->categories_id == $category->id) {
                    array_push($array_id, $post->id);
                }
            }
        }

        return $array_id;
    }

    public function getEventArrayID($category)
    {
        $event = Event::all();

        $array_id = array();

        for ($i = 0; $i < count($event); $i++) {
            $tags = json_decode($event[$i]->categories);

            for ($k = 0; $k < count($tags); $k++) {
                if ($tags[$k]->categories_id == $category->id) {
                    array_push($array_id, $event[$i]->id);
                }
            }
        }

        return $array_id;
    }
}
