<?php

namespace App\Observers;

use App\Modules\Backend\Subscribes\Models\Subscribe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SubscribeObserver
{
    /**
     * Handle the subscribe "created" event.
     *
     * @param  \App\Modules\Backend\Subscribes\Models\Subscribe  $subscribe
     * @return void
     */
    public function created(Subscribe $subscribe)
    {
        //
    }

    /**
     * Handle the subscribe "saving" event.
     *
     * @param  \App\Modules\Backend\Subscribes\Models\Subscribe  $subscribe
     * @return void
     */
    public function saving(Subscribe $subscribe)
    {
        if (Auth::check() && Auth::id() == $subscribe->user_id) {
            $json_column = array();

            $column = Schema::getColumnListing('subscribes');

            foreach ($column as $key => $value) {
                $type = DB::connection()->getDoctrineColumn('subscribes', $value)->getType()->getName();
                if ($type == 'json') {
                    array_push($json_column, $value);
                }
            }

            foreach ($json_column as $key => $value) {
                $cache_value = array();
                if (Cache::has('_' . Auth::id() . '_' . $value)) {
                    Cache::forget('_' . Auth::id() . '_' . $value);
                } else {
                    if ($subscribe->{$value} != null) {
                        foreach ($subscribe->{$value} as $index => $item) {
                            for ($i = 0; $i < count($item); $i++) {
                                array_push($cache_value, $item[$i]);
                            }
                        }
                        Cache::store('database')->put('_' . Auth::id() . '_' . $value, $cache_value, Config::get('cache.lifetime'));
                    }
                }
            }
        }
    }

    /**
     * Handle the subscribe "updated" event.
     *
     * @param  \App\Modules\Backend\Subscribes\Models\Subscribe  $subscribe
     * @return void
     */
    public function updated(Subscribe $subscribe)
    {
        //
    }

    /**
     * Handle the subscribe "deleted" event.
     *
     * @param  \App\Modules\Backend\Subscribes\Models\Subscribe  $subscribe
     * @return void
     */
    public function deleted(Subscribe $subscribe)
    {
        //
    }

    /**
     * Handle the subscribe "restored" event.
     *
     * @param  \App\Modules\Backend\Subscribes\Models\Subscribe  $subscribe
     * @return void
     */
    public function restored(Subscribe $subscribe)
    {
        //
    }

    /**
     * Handle the subscribe "force deleted" event.
     *
     * @param  \App\Modules\Backend\Subscribes\Models\Subscribe  $subscribe
     * @return void
     */
    public function forceDeleted(Subscribe $subscribe)
    {
        //
    }
}
