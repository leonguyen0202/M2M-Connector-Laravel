<?php
$namespace = 'App\Modules\Frontend\Botman\Controllers';
Route::group(
    ['module' => 'Botman', 'namespace' => $namespace, 'middleware' => ['web']],
    function () {
        Route::match(['get', 'post'], '/botman', 'BotManController@handle')->name('botman');

        Route::get('/chatbot/filter/keyword={keyword}&&language_code={language_code}', 'BotManController@filter');
    }
);