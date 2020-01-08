<?php

$namespace = 'App\Modules\Backend\Events\Controllers';

Route::group(
    ['middleware' => ['web','auth'], 'prefix' => 'dashboard', 'module' => 'Events', 'namespace' => $namespace],
    function () {
        Route::post('/events/render_event', [
            'as' => 'events.render',
            'uses' => 'EventController@render_event'
        ]);

        Route::get('/events', [
            'as' => 'events.index',
            'uses' => 'EventController@index'
        ]);
});
