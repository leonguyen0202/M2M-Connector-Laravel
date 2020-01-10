<?php

namespace App\Http\Middleware;

use Closure;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Sending;

class TypingMiddleware implements Sending
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function sending($payload, $next, BotMan $bot)
    {
        $bot->typesAndWaits(1);

        return $next($payload);
    }
}
