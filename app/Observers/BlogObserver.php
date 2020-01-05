<?php

namespace App\Observers;

use App\Modules\Backend\Blogs\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class BlogObserver
{
    /**
     * Handle the blog "created" event.
     *
     * @param  \App\Blog  $blog
     * @return void
     */
    public function created(Blog $blog)
    {
        if (Auth::check() && Auth::id() == $blog->author_id) {
            if (Cache::has('_' . Auth::id() . '_blog_data')) {
                Cache::forget('_' . Auth::id() . '_blog_data');
            }

            $data = collect([]);

            $posts = Auth::user()->has_blogs;

            foreach ($posts as $key => $value) {
                $data->push($value);
            }

            Cache::store('database')->put('_' . Auth::id() . '_blog_data', $data, Config::get('cache.lifetime'));
        }
    }

    /**
     * Handle the blog "saved" event.
     *
     * @param  \App\Blog  $blog
     * @return void
     */
    public function saving(Blog $blog)
    {
        if (Auth::check() && Auth::id() == $blog->author_id) {
            if (Cache::has('_' . Auth::id() . '_blog_data')) {
                Cache::forget('_' . Auth::id() . '_blog_data');
            }

            $data = collect([]);

            $posts = Auth::user()->has_blogs;

            foreach ($posts as $key => $value) {
                $data->push($value);
            }

            Cache::store('database')->put('_' . Auth::id() . '_blog_data', $data, Config::get('cache.lifetime'));
        }
    }

    /**
     * Handle the blog "updated" event.
     *
     * @param  \App\Blog  $blog
     * @return void
     */
    public function updated(Blog $blog)
    {
        if (Auth::check() && Auth::id() == $blog->author_id) {
            if (Cache::has('_' . Auth::id() . '_blog_data')) {
                Cache::forget('_' . Auth::id() . '_blog_data');
            }

            $data = collect([]);

            $posts = Auth::user()->has_blogs;

            foreach ($posts as $key => $value) {
                $data->push($value);
            }

            Cache::store('database')->put('_' . Auth::id() . '_blog_data', $data, Config::get('cache.lifetime'));
        }
    }

    /**
     * Handle the blog "deleted" event.
     *
     * @param  \App\Blog  $blog
     * @return void
     */
    public function deleted(Blog $blog)
    {
        //
    }

    /**
     * Handle the blog "restored" event.
     *
     * @param  \App\Blog  $blog
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
