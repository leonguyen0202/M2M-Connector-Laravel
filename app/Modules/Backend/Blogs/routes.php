<?php

$namespace = 'App\Modules\Backend\Blogs\Controllers';

Route::group(
    ['middleware' => ['web','auth'], 'prefix' => 'dashboard', 'module' => 'Blogs', 'namespace' => $namespace],
    function () {
        Route::post('/blog/table/view', [
            'as' => 'blogs.table.view',
            'uses' => 'BlogController@table'
        ]);

        Route::post('/blog/tinymce/description', [
            'as' => 'blogs.tinymce.description',
            'uses' => 'BlogController@ajax_tinyMCE_description'
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
});
