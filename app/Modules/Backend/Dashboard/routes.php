<?php

$namespace = 'App\Modules\Backend\Dashboard\Controllers';

Route::group(
    ['middleware' => ['web','auth'], 'prefix' => 'dashboard', 'module' => 'Dashboard', 'namespace' => $namespace],
    function () {
        Route::get('/sidebar-mini', [
            'as' => 'dashboard.sidebar.status',
            'uses' => 'DashboardController@minimizeSidebar'
        ]);

        Route::get('/', [
            'as' => 'dashboard.index',
            'uses' => 'DashboardController@index'
        ]);
});
