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

Route::get('/', 'web\SignupController@getLogin');

Route::get('signup', 'web\SignupController@getLogin');
Route::post('signup', 'web\SignupController@postSignUp');

Route::post('login', 'web\SignupController@postLogin');
Route::get('login', 'web\SignupController@getLogin');
Route::get('verification-code/{code}', 'web\SignupController@getVerificationCode');
Route::get('user-activation/{code}','Api\UserApiController@getActivatejobseeker');
Route::get('logout', 'web\SignupController@logout');

Route::get('/aboutus', function () {
    return view('about');
});

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'recruiter'], function () {
        Route::group(['middleware' => 'acceptedTerms'], function () {
            Route::get('home', 'web\SignupController@dashboard');
            Route::get('jobtemplates', 'web\JobtemplateController@listJobTemplates');
            Route::get('jobtemplates/create', 'web\JobtemplateController@createJobTemplate');
            Route::post('jobtemplates/saveOrUpdate', 'web\JobtemplateController@saveOrUpdate');
            Route::get('createJob/{templateId}', 'web\RecruiterJobController@createJob');
        });

        Route::group(['middleware' => 'termCondition'], function () {
            Route::get('terms-conditions', 'web\SignupController@getTermsAndCondition');
            Route::get('tutorial', 'web\SignupController@getTutorial');
        });
    });
});

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
    
    Route::group(['prefix' => 'location/'], function() {
        Route::get('index', 'Cms\LocationController@index');
        Route::get('list', 'Cms\LocationController@locationsList');
        Route::delete('{id}/delete', 'Cms\LocationController@delete');
        Route::get('{id}/edit', 'Cms\LocationController@edit');
        Route::get('create', 'Cms\LocationController@create');
        Route::post('store', 'Cms\LocationController@store');
    });
});

