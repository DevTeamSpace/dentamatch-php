<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');*/

Route::post('users/sign-up','Api\UserApiController@postSignup');
Route::post('users/sign-in','Api\UserApiController@postSignIn');
Route::get('privacy-policy','Api\UserApiController@getPrivacypolicy');
Route::get('term-condition','Api\UserApiController@getTermsAndCondition');
Route::put('users/forgot-password', 'api\UserApiController@putForgotPassword');
