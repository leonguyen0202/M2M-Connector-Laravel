<?php

$namespace = 'App\Modules\Backend\Events\Controllers';

Route::group(
    ['middleware' => ['web','auth'], 'prefix' => 'dashboard', 'module' => 'Events', 'namespace' => $namespace],
    function () {
        Route::get('/events', [
            'as' => 'events.index',
            'uses' => 'EventController@index'
        ]);
});
