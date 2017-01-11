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
Route::put('users/reset-password', 'api\UserApiController@putForgotPassword');
Route::post('users/change-password','Api\UserProfileApiController@postChangePassword');
Route::get('list-jobtitle','Api\MasterApiController@getJobTitlelists');
Route::get('list-skills','Api\MasterApiController@getSkilllists');

Route::post('users/upload-image', 'api\UserProfileApiController@postUploadImage');
Route::put('users/update-license', 'api\UserProfileApiController@putUpdateLicense');
Route::post('users/work-experience', 'api\WorkExperienceApiController@postWorkExperince');
Route::delete('users/work-experience/{id}', 'api\WorkExperienceApiController@deleteWorkExperince');
Route::post('users/work-experience-list', 'api\WorkExperienceApiController@postListWorkExperience');

