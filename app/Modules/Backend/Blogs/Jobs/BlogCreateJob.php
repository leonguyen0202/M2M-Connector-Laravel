<?php

namespace App\Modules\Backend\Blogs\Jobs;

use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\Settings\Models\Developer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;

    public $name;

    public $fileName;

    public $array_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, $name, $fileName, array $array_data)
    {
        $this->files = $file;
        $this->name = $name;
        $this->fileName = $fileName;
        $this->array_data = $array_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $blog = Blog::create($);
    }
}
