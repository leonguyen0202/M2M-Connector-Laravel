<?php

namespace App\Jobs;

use App\Modules\Backend\Blogs\Models\Blog;
use App\Modules\Backend\DeveloperSettings\Models\Developer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCRUDJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $method;

    protected $array;

    public $tries = 10;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($method, array $array)
    {
        $this->method = $method;
        $this->array = $array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $languages = DB::table('localization')->select('locale_code')->get();

        $destination_path = Developer::query()->where([['type', '=', 'upload']])->first();

        foreach ($languages as $index => $value) {
            unset(($this->array)[($value->locale_code) . '_slug']);
        }

        if (strtolower($this->method) == 'post') {

            $blog = new Blog();

            foreach ($this->array as $key => $value) {
                $blog->{$key} = $value;
            }

            $blog->save();

            if (!($item->getMedia('blog-images'))->isEmpty()) {
                $item->clearMediaCollection('blog-images');
            }

            if ($item->background_image == "default-background.jpg") {
                $item->copyMedia(public_path('images/default/' . $item->background_image))->toMediaCollection('blog-images');
            } else {
                $item->addMedia(public_path(($this->upload_path)->details['path'][0]. '/posts/' . $item->background_image))->toMediaCollection('blog-images');
            }

        } else if (($this->method)->isMethod('put')) {

        } else if (strtolower($this->method) == 'delete') {

        } else {
            abort(404);
        }
    }

    protected function check_null($title)
    {
        if (empty($title)) {
            return null;
        }
        return Str::slug($title, '-');
    }

    protected function convert_json()
    {
        # code...
    }
}
