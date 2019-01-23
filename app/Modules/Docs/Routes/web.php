<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'docs'], function() {
    Route::get('/', 'WebController@index');
    Route::get('/api-doc', 'WebController@getApi');
    Route::get('/web-doc', 'WebController@getWeb');
});
