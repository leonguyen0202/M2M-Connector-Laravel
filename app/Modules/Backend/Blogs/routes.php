<?php

use Illuminate\Support\Facades\DB;

$languages = DB::table('localization')->select('locale_code')->get();

$namespace = 'App\Modules\Backend\Blogs\Controllers';

Route::group(
    ['middleware' => ['web','auth'], 'prefix' => 'dashboard', 'module' => 'Blogs', 'namespace' => $namespace],
    function () use ($languages) {
        Route::post('/blog/table/view', [
            'as' => 'blogs.table.view',
            'uses' => 'BlogController@table'
        ]);

        Route::get('/blogs', [
            'as' => 'blogs.index',
            'uses' => 'BlogController@index'
        ]);

        Route::get('/blog/create', [
            'as' => 'blogs.create',
            'uses' => 'BlogController@create'
        ]);

        // foreach ($languages as $key => $value) {
        //     Route::post('/blog/store/'.$value->locale_code, [
        //         'as' => 'blogs.store.'.$value->locale_code,
        //         'uses' => 'BlogController@store'
        //     ]);
        // }

        Route::post('/blog/store', [
            'as' => 'blogs.store',
            'uses' => 'BlogController@store'
        ]);

        Route::get('/blog/view/{view}', [
            'as' => 'blogs.view',
            'uses' => 'BlogController@switchView'
        ]);

        Route::post('/blog/test', [
            'as' => 'blogs.request.test',
            'uses' => 'BlogController@testRequest'
        ]);

        Route::delete('/blog/delete', [
            'as' => 'blogs.delete',
            'uses' => 'BlogController@delete'  
        ]);
});
