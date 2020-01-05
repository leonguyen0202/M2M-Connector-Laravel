<?php
$namespace = 'App\Modules\Frontend\Home\Controllers';
Route::group(
    ['module' => 'Home', 'namespace' => $namespace, 'middleware' => ['web']],
    function () {
        Route::get('/locale/{locale}', [
            'as' => 'locale',
            'uses' => 'HomeController@switchLang'
        ]);

        Route::get('/', [
            // 'middleware' => ['permission:access-dashboard'],
            'as' => 'home.index',
            'uses' => 'HomeController@index',
        ]);

        Route::post('/subscribe', [
            'as' => 'home.subscribe',
            'uses' => 'HomeController@subscribe',
        ]);

        Route::post('/action', [
            'as' => 'home.action',
            'uses' => 'HomeController@action',
        ]);
    }
);