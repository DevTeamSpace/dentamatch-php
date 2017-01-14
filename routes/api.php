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
Route::put('users/forgot-password', 'Api\UserApiController@putForgotPassword');
Route::put('users/reset-password', 'Api\UserApiController@putForgotPassword');
Route::post('users/change-password','Api\UserProfileApiController@postChangePassword');
Route::get('list-jobtitle','Api\WorkExperienceApiController@getJobTitlelists');
Route::get('list-skills','Api\SkillApiController@getSkilllists');
Route::post('users/update-skill','Api\SkillApiController@postUpdateSkills');
Route::get('list-certifications','Api\SkillApiController@getCertificationListing');
Route::post('users/update-certificate','Api\SkillApiController@postUpdateCertifications');

Route::post('users/upload-image', 'Api\UserProfileApiController@postUploadImage');
Route::put('users/update-license', 'Api\UserProfileApiController@putUpdateLicense');
Route::post('users/work-experience-save', 'Api\WorkExperienceApiController@postWorkExperience');
Route::delete('users/work-experience-delete', 'Api\WorkExperienceApiController@deleteWorkExperience');
Route::post('users/work-experience-list', 'Api\WorkExperienceApiController@postListWorkExperience');
Route::get('users/school-list', 'Api\WorkExperienceApiController@getSchoolList');
Route::post('users/school-add', 'Api\WorkExperienceApiController@postSchoolSaveUpdate');
Route::get('users/affiliation-list', 'Api\AffiliationsApiController@getAffiliationList');
Route::post('users/affiliation-save', 'Api\AffiliationsApiController@postAffiliationSaveUpdate');
Route::post('users/about-me-save', 'Api\UserProfileApiController@postAboutMe');
Route::get('users/about-me-list', 'Api\UserProfileApiController@getAboutMe');

