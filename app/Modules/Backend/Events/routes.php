<?php

$namespace = 'App\Modules\Backend\Events\Controllers';

Route::group(
    ['middleware' => ['web','auth'], 'prefix' => 'dashboard', 'module' => 'Events', 'namespace' => $namespace],
    function () {
        Route::get('/events/images/image-test', [
            'as' => 'events.image_test',
            'uses' => 'EventController@image_test'
        ]);

        Route::post('/data_soruce', [
            'as' => 'events.data.source',
            'uses' => 'EventController@event_categories_data_source'
        ]);

        Route::get('/events/check_title/{title}', [
            'as' => 'events.check',
            'uses' => 'EventController@check_title'
        ]);

        Route::post('/events/render_event', [
            'as' => 'events.render',
            'uses' => 'EventController@render_event'
        ]);

        Route::resource('events', 'EventController')->parameters([
            'events' => 'title'
        ])->except(['create','edit']);
});
