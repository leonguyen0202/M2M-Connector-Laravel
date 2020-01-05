<?php

namespace App\Jobs;

use App\Modules\Backend\Subscribes\Models\Subscribe;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class SubscribeActionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;

    protected $url;

    protected $subscriber;

    protected $user;

    protected $cookie;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $type, $url = array(), $subscriber, User $user, $cookie)
    {
        $this->type = $type;
        $this->url = $url;
        $this->user = $user;
        $this->subscriber = $subscriber;
        $this->cookie = $cookie;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (strtolower($this->type) == 'blogs' || strtolower($this->type) == 'events' || strtolower($this->type) == 'categories' || strtolower($this->type) == 'users') {
            $column = Str::plural(strtolower($this->type));

            if (($this->url)[0] == 'blog' || ($this->url)[0] == 'event') {
                $record = ($this->converting_model(Str::plural(ucfirst(($this->url)[0])), Str::singular(ucfirst(($this->url)[0]))))::query()->where([
                    [ ($this->cookie) . '_slug', '=', ($this->url)[1]],
                ])->first();

                if ($record == null) {
                    $record = ($this->converting_model(Str::plural(ucfirst(($this->url)[0])), Str::singular(ucfirst(($this->url)[0]))))::query()->where([
                        ['en_slug', '=', ($this->url)[1]],
                    ])->first();
                }

                if ($record == null) {
                    return ;
                }

                if ($column == 'users') {
                    $record = $record->author;
                }
            } else if (($this->url)[0] == 'user') {

            } else if (($this->url)[0] == 'category') {
                $record = ($this->converting_model(Str::plural(ucfirst(($this->url)[0])), Str::singular(ucfirst(($this->url)[0]))))::query()->where([
                    ['slug', '=', ($this->url)[1]],
                ])->first();
            }
        }

        if (($this->subscriber) != null) {
            $data = json_return(($this->subscriber)->{$column}, $record, $column);

            ($this->subscriber)->{$column} = $data;

            ($this->subscriber)->save();
        } else {
            Subscribe::create([
                'user_id' => ($this->user)->id,
                'email' => ($this->user)->email,
                '' . $column . '' => json_return(null, $record, $column),
            ]);
        }
    }

    protected function converting_model($plural, $singular)
    {
        return "App\\Modules\\Backend\\" . $plural . "\\Models\\" . $singular;
    }
}
