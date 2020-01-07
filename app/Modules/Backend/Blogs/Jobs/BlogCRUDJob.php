<?php

namespace App\Modules\Backend\Blogs\Jobs;

use App\Modules\Backend\Blogs\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class BlogCRUDJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $method;

    public $blog_id;

    public $array_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($blog_id, array $array_data, $method)
    {
        $this->blog_id = $blog_id;
        $this->array_data = $array_data;
        $this->method = $method;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $languages = DB::table('localization')->select('locale_name', 'locale_code')->get();

        if (strtolower($this->method) == 'post') {

            $blog = Blog::create($this->array_data);

        } else if (strtolower($this->method) == 'put') {

            $blog = Blog::find($this->blog_id)->update($this->array_data);

        } else if (strtolower($this->method) == 'delete') {
            
            $blog = Blog::find($this->blog_id);

            $blog->delete();
            
        }
    }
}
