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

        Route::get('/blog/view/{view}', [
            'as' => 'blogs.view',
            'uses' => 'BlogController@switchView'
        ]);

        Route::get('/blog/create', [
            'as' => 'blogs.create',
            'uses' => 'BlogController@create'
        ]);

        Route::get('/blog/edit/{slug}', [
            'as' => 'blogs.edit',
            'uses' => 'BlogController@edit'
        ]);

        Route::resource('blogs', 'BlogController')->parameters([
            'blogs' => 'slug'
        ])->except(['create','edit']);

        // Route::get('/blogs', [
        //     'as' => 'blogs.index',
        //     'uses' => 'BlogController@index'
        // ]);

        // Route::get('/blog/create', [
        //     'as' => 'blogs.create',
        //     'uses' => 'BlogController@create'
        // ]);

        // Route::post('/blog/store', [
        //     'as' => 'blogs.store',
        //     'uses' => 'BlogController@store'
        // ]);

        // Route::delete('/blog/delete/{slug}', [
        //     'as' => 'blogs.delete',
        //     'uses' => 'BlogController@delete'  
        // ]);
});
