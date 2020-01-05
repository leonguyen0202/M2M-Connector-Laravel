<?php
$namespace = 'App\Modules\Frontend\Event\Controllers';
Route::group(
    ['module' => 'Event', 'namespace' => $namespace, 'middleware' => ['web']],
    function () {
        Route::get('events', [
            'as' => 'event.index',
            'uses' => 'EventController@index',
        ]);

        Route::get('event/{slug}', [
            'as' => 'event.detail',
            'uses' => 'EventController@detail',
        ]);
    }
);
