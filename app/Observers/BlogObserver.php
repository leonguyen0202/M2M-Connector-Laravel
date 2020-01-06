<?php

namespace App\Observers;

use App\Modules\Backend\Blogs\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\User;

class BlogObserver
{
    /**
     * Handle the blog "created" event.
     *
     * @param  \App\Modules\Backend\Blogs\Models\Blog  $blog
     * @return void
     */
    public function created(Blog $blog)
    {
        if ($blog->background_image != 'default-background.jpg') {
            $name = explode(".", $blog->background_image);

            $blog->add_media_from_disk($name[0], $blog->background_image);
        }

        $user = $blog->author;

        if (Cache::has('_'. $user->id . '_blog_data')) {
            Cache::forget('_'. $user->id . '_blog_data');
            Cache::store('database')->put('_'. $user->id . '_blog_data', $user->has_blogs, Config::get('cache.lifetime'));
        }
    }

    /**
     * Handle the blog "updated" event.
     *
     * @param  \App\Modules\Backend\Blogs\Models\Blog  $blog
     * @return void
     */
    public function updated(Blog $blog)
    {
        if ($blog->isDirty('background_image')) {
            if (!($blog->getMedia('blog-images'))->isEmpty()) {
                $blog->clearMediaCollection('blog-images');
            }

            $name = explode(".", $blog->background_image);

            $blog->add_media_from_disk($name[0], $blog->background_image);
        }
        $user = $blog->author;

        if (Cache::has('_'. $user->id . '_blog_data')) {
            Cache::forget('_'. $user->id . '_blog_data');
            Cache::store('database')->put('_'. $user->id . '_blog_data', $user->has_blogs, Config::get('cache.lifetime'));
        }
    }

    /**
     * Handle the blog "deleted" event.
     *
     * @param  \App\Modules\Backend\Blogs\Models\Blog  $blog
     * @return void
     */
    public function deleted(Blog $blog)
    {
        if (!($blog->getMedia('blog-images'))->isEmpty()) {
            $blog->clearMediaCollection('blog-images');
        }

        $user = $blog->author;

        if (Cache::has('_'. $user->id . '_blog_data')) {
            Cache::forget('_'. $user->id . '_blog_data');
            Cache::store('database')->put('_'. $user->id . '_blog_data', $user->has_blogs, Config::get('cache.lifetime'));
        }
    }

    /**
     * Handle the blog "restored" event.
     *
     * @param  \App\Modules\Backend\Blogs\Models\Blog  $blog
     * @return void
     */
    public function restored(Blog $blog)
    {
        //
    }

    /**
     * Handle the blog "force deleted" event.
     *
     * @param  \App\Blog  $blog
     * @return void
     */
    public function forceDeleted(Blog $blog)
    {
        //
    }
}
