<?php

namespace App\Modules\Backend\Events\Jobs;

use App\Modules\Backend\Events\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class EventCRUDJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $method;

    public $id;

    public $array_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, array $array_data, $method)
    {
        $this->id = $id;
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

            $blog = Event::create($this->array_data);

        } else if (strtolower($this->method) == 'put') {

            $blog = Event::find($this->id)->update($this->array_data);

        } else if (strtolower($this->method) == 'delete') {
            
            $blog = Event::find($this->id);

            $blog->delete();
            
        }
    }
}
