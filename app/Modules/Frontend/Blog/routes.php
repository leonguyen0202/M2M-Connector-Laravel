<?php
$namespace = 'App\Modules\Frontend\Blog\Controllers';
Route::group(
    ['module' => 'Blog', 'namespace' => $namespace, 'middleware' => ['web']],
    function () {
        Route::get('blogs', [
            'as' => 'blog.index',
            'uses' => 'BlogController@index',
        ]);

        Route::get('blog/{slug}', [
            // 'middleware' => ['permission:access-dashboard'],
            'as' => 'blog.detail',
            'uses' => 'BlogController@detail',
        ]);
    }
);
