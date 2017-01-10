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

Route::get('terms-conditions', 'web\SignupController@getTermsAndCondition');
Route::get('logout', 'web\SignupController@logout');
Route::get('dashboard', 'web\SignupController@dashboard');

Route::get('/aboutus', function () {
    return view('about');
});

Route::get('user-activation/{code}','Api\UserApiController@getActivatejobseeker');

