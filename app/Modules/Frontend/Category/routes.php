<?php
$namespace = 'App\Modules\Frontend\Category\Controllers';
Route::group(
    ['module' => 'Category', 'namespace' => $namespace, 'middleware' => ['web']],
    function () {
        Route::get('categories', [
            'as' => 'categories.index',
            'uses' => 'CategoryController@index',
        ]);

        Route::get('category/{slug}', [
            'as' => 'category.detail',
            'uses' => 'CategoryController@detail',
        ]);
    }
);
