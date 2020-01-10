<?php

namespace App\Modules\Backend\Subscribes\Jobs;

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

    protected $user;

    protected $result;

    protected $column_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $column_name, array $result)
    {
        $this->user = $user;
        $this->column_name = $column_name;
        $this->result = $result;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $has_subscribe = Subscribe::query()->where([
            ['email', '=', ($this->user)->email]
        ])->first();

        if ($has_subscribe) {
            
            $has_subscribe->{ ($this->column_name) } = [ ($this->column_name) => ($this->result) ];

            $has_subscribe->save();
        } else {
            Subscribe::create([
                'user_id' => ($this->user)->id,
                'email' => ($this->user)->email,
                '' . ($this->column_name) . '' => [ ($this->column_name) => ($this->result) ]
            ]);
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
