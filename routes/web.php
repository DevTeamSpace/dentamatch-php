<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/aboutus', function () {
    return view('about');
});

Route::get('user-activation/{code}','Api\UserApiController@getActivatejobseeker');

//dd(bcrypt('123456'));
Route::group(['middleware' => 'web', 'prefix' => 'cms/'], function () {
    
    Route::get('login', 'Auth\LoginController@getLogin');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/', 'Cms\UserController@index');
    Route::get('/home', 'cms\HomeController@index');
    Route::group(['prefix' => 'user/'], function() {
        Route::get('listPhotographer', 'cms\UserController@getPhotographerList');
        Route::get('listConsumer', 'cms\UserController@getConsumerList');
        Route::get('listDesigner', 'cms\UserController@getDesignerList');
        Route::get('searchDesignerList', 'cms\UserController@searchDesignerList');  
        Route::get('create', 'cms\UserController@createUser');
        Route::post('store', 'cms\UserController@store');
        Route::post('reject', 'cms\UserController@reject');
        Route::post('deactivate', 'cms\UserController@deactivate');
        Route::get('{id}/view', 'cms\UserController@view');
        Route::get('{id}/activeOrInactive', 'cms\UserController@updateActiveStatus');
        Route::get('index', 'cms\UserController@index');
        Route::get('', 'cms\UserController@index');
        Route::get('changePassword', 'cms\UserController@changePassword');
        Route::post('updatePassword', 'cms\UserController@updatePassword');
    });
});

