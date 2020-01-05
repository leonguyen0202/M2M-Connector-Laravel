<?php

namespace App\Jobs;

use App\Modules\Backend\Subscribes\Models\Subscribe;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class SubscribeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->table = 'subscribes';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $json_column = array();

        if ($this->model instanceof \App\User) {
            
            $subscribe = Subscribe::query()->where([
                ['email', '=', ($this->model)->email],
            ])->first();

        }  else if ($this->model instanceof \App\Modules\Backend\Subscribes\Models\Subscribe) {
            $subscribe = Subscribe::find( ($this->model)->id );
        }

        if ($subscribe) {
            $columns = $this->getColumns();

            foreach ($columns as $key => $value) {
                $type = DB::connection()->getDoctrineColumn('subscribes', $value)->getType()->getName();

                if ($type == 'json') {
                    array_push($json_column, $value);
                }
            }

            foreach ($json_column as $k => $v) {
                
                $cache_value = array();

                if ($subscribe->{$v} != null) {
                    foreach ($subscribe->{$v} as $key => $value) {
                        foreach ($value as $index => $item) {
                            array_push($cache_value, $item);
                        }
                    }
                    Cache::store('database')->put('_'.($this->model)->id.'_'.$v, $cache_value, Config::get('cache.lifetime'));
                }
            }
        }

    }

    /**
     * Get Columns of table
     */
    protected function getColumns()
    {
        return Schema::getColumnListing('subscribes');
    }
}
