<?php

namespace App\Jobs;

use App\Modules\Backend\Blogs\Models\Blog;
use App\Traits\BotmanTraits;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DatabaseCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BotmanTraits;

    protected $value;

    public $tries = 10;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->value instanceof \App\Modules\Backend\Categories\Models\Category) {
            $post_id = array();

            $top_posts = Blog::query()->whereJsonContains('categories->categories_id', ($this->value)->id)->orderBy('visits', 'DESC')->limit(2)->get();

            for ($i = 0; $i < count($top_posts); $i++) {
                array_push($post_id, $top_posts[$i]->id);
            }

            Cache::store('database')->put('_' . ($this->value)->slug . '_top_posts', $top_posts, Config::get('cache.lifetime'));

            $contributors = Blog::query()
                ->whereJsonContains('categories->categories_id', ($this->value)->id)->select(
                DB::raw('COUNT(*) as total'),
                'author_id'
            )->groupBy('author_id')
                ->orderBy('total', 'DESC')->limit(3)->get();

            Cache::store('database')->put('_' . ($this->value)->slug . '_top_contributors', $contributors, Config::get('cache.lifetime'));
        }
    }
}
