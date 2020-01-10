<?php

$namespace = 'App\Modules\Backend\Events\Controllers';

Route::group(
    ['middleware' => ['web','auth'], 'prefix' => 'dashboard', 'module' => 'Events', 'namespace' => $namespace],
    function () {
        Route::get('/events/selectable', function ()
        {
            return false;
        });
        Route::get('/events/check_title/{title}', [
            'as' => 'events.check',
            'uses' => 'EventController@check_title'
        ]);

        Route::post('/events/render_event', [
            'as' => 'events.render',
            'uses' => 'EventController@render_event'
        ]);

        Route::resource('events', 'EventController')->parameters([
            'events' => 'slug'
        ])->except(['create','edit']);
});
