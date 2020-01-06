<?php

namespace App\Modules\Backend\Events\Jobs;

use App\Modules\Backend\Blogs\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\User;

class EventStatusUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $method;

    public $file;

    public $name;

    public $fileName;

    public $array_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, $name, $fileName, array $array_data, $method)
    {
        $this->files = $file;
        $this->name = $name;
        $this->fileName = $fileName;
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
        if (strtolower($this->method) == 'post') {
            $blog = Blog::create($this->array_data);
        } else if (strtolower($this->method) == 'put') {
            # code...
        }
    }
}
