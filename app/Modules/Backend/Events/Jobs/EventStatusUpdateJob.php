<?php

namespace App\Modules\Backend\Events\Jobs;

use App\Modules\Backend\Events\Models\Event;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class EventStatusUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (DB::table('jobs')->count() == 0) {
            // DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::statement("ALTER TABLE jobs AUTO_INCREMENT = 1");
            // DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
        
        Event::query()->where([
            ['type', '=', 'event'],
            ['is_completed', '=', '0'],
            ['end', '<', Carbon::now()->toDateTimeString()],
        ])->update([
            'is_completed' => '1',
        ]);
    }
}
