<?php

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
Route::group(['prefix'=>'v1/','middleware' => ['xss']], function () {
    Route::post('users/sign-up','Api\v1\UserApiController@postSignup');
    Route::post('users/sign-in','Api\v1\UserApiController@postSignIn');
    Route::get('users/is-verified','Api\v1\UserProfileApiController@getIsUserVerified');
    Route::delete('users/sign-out','Api\v1\UserApiController@deleteSignOut');
    Route::get('privacy-policy','Api\v1\UserApiController@getPrivacypolicy');
    Route::get('term-condition','Api\v1\UserApiController@getTermsAndCondition');
    Route::put('users/forgot-password', 'Api\v1\UserApiController@putForgotPassword');
    Route::put('users/reset-password', 'Api\v1\UserApiController@putForgotPassword');
    Route::post('users/change-password','Api\v1\UserProfileApiController@postChangePassword');
    Route::get('list-jobtitle','Api\v1\WorkExperienceApiController@getJobTitlelists');
    Route::get('list-skills','Api\v1\SkillApiController@getSkilllists');
    Route::post('users/update-skill','Api\v1\SkillApiController@postUpdateSkills');
    Route::get('list-certifications','Api\v1\SkillApiController@getCertificationListing');
    Route::post('users/update-certificate','Api\v1\SkillApiController@postUpdateCertifications');
    Route::post('users/update-certificate-validity','Api\v1\SkillApiController@postUpdateCertificationsValidity');

    Route::post('users/upload-image', 'Api\v1\UserProfileApiController@postUploadImage');
    Route::put('users/update-license', 'Api\v1\UserProfileApiController@putUpdateLicense');
    Route::post('users/work-experience-save', 'Api\v1\WorkExperienceApiController@postWorkExperience');
    Route::delete('users/work-experience-delete', 'Api\v1\WorkExperienceApiController@deleteWorkExperience');
    Route::post('users/work-experience-list', 'Api\v1\WorkExperienceApiController@postListWorkExperience');
    Route::get('users/school-list', 'Api\v1\WorkExperienceApiController@getSchoolList');
    Route::post('users/school-add', 'Api\v1\WorkExperienceApiController@postSchoolSaveUpdate');
    Route::get('users/affiliation-list', 'Api\v1\AffiliationsApiController@getAffiliationList');
    Route::post('users/affiliation-save', 'Api\v1\AffiliationsApiController@postAffiliationSaveUpdate');
    Route::post('users/about-me-save', 'Api\v1\UserProfileApiController@postAboutMe');
    Route::get('users/about-me-list', 'Api\v1\UserProfileApiController@getAboutMe');


    Route::get('users/search-job', 'Api\v1\SkillApiController@getJobSearch');
    Route::get('users/user-profile', 'Api\v1\UserProfileApiController@getUserProfile');
    Route::put('users/user-profile-update', 'Api\v1\UserProfileApiController@updateUserProfile');
    Route::post('users/user-location-update', 'Api\v1\UserProfileApiController@updateUserLocationUpdate');

    Route::post('users/acceptreject-job', 'Api\v1\SearchApiController@postAcceptRejectInvitedJob');



    Route::post('users/update-availability', 'Api\v1\CalendarApiController@postJobAvailability');
    Route::post('users/search-jobs', 'Api\v1\SearchApiController@postSearchjobs');
    Route::get('users/chat-user-list', 'Api\v1\UserApiController@chatRecruiterList');
    Route::post('users/chat-user-block-unblock', 'Api\v1\UserApiController@chatBlockUnblockRecruiter');
    Route::post('users/save-job', 'Api\v1\SearchApiController@postSaveUnsavejob');
    Route::post('users/apply-job', 'Api\v1\SearchApiController@postApplyJob');
    Route::post('users/cancel-job', 'Api\v1\SearchApiController@postCancelJob');
    Route::get('users/job-list', 'Api\v1\SearchApiController@getJobList');
    Route::get('users/jobs', 'Api\v1\SearchApiController@getJobList1');
    Route::post('jobs/job-detail', 'Api\v1\SearchApiController@postJobDetail');
    Route::post('jobs/hired-jobs', 'Api\v1\CalendarApiController@postHiredJobsByDate');

    Route::post('users/availability-list', 'Api\v1\CalendarApiController@postAvailability');

    Route::get('users/notification-list', 'Api\v1\PushNotificationApiController@getNotificationlists');
    Route::post('users/notification-read', 'Api\v1\PushNotificationApiController@PostUpdateNotification');
    Route::get('users/unread-notification', 'Api\v1\PushNotificationApiController@GetunreadNotification');
    Route::post('users/delete-notification', 'Api\v1\PushNotificationApiController@PostDeleteNotification');

    Route::post('chat/send-message', 'Api\v1\PushNotificationApiController@userChatNotification');
    Route::post('users/update-devicetoken', 'Api\v1\PushNotificationApiController@PostUpdateDeviceToken');

    Route::post('admin/forgot-password', 'Api\v1\UserApiController@postAdminForgotPassword');
    
    Route::get('jobs/preferred-job-locations', 'Api\v1\\MasterApiController@getPreferrefJobLocation');
       
});
