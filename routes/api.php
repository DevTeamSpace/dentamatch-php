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
    Route::post('users/sign-up','v1\Api\UserApiController@postSignup');
    Route::post('users/sign-in','v1\Api\UserApiController@postSignIn');
    Route::get('users/is-verified','v1\Api\UserProfileApiController@getIsUserVerified');
    Route::delete('users/sign-out','v1\Api\UserApiController@deleteSignOut');
    Route::get('privacy-policy','v1\Api\UserApiController@getPrivacypolicy');
    Route::get('term-condition','v1\Api\UserApiController@getTermsAndCondition');
    Route::put('users/forgot-password', 'v1\Api\UserApiController@putForgotPassword');
    Route::put('users/reset-password', 'v1\Api\UserApiController@putForgotPassword');
    Route::post('users/change-password','v1\Api\UserProfileApiController@postChangePassword');
    Route::get('list-jobtitle','v1\Api\WorkExperienceApiController@getJobTitlelists');
    Route::get('list-skills','v1\Api\SkillApiController@getSkilllists');
    Route::post('users/update-skill','v1\Api\SkillApiController@postUpdateSkills');
    Route::get('list-certifications','v1\Api\SkillApiController@getCertificationListing');
    Route::post('users/update-certificate','v1\Api\SkillApiController@postUpdateCertifications');
    Route::post('users/update-certificate-validity','v1\Api\SkillApiController@postUpdateCertificationsValidity');

    Route::post('users/upload-image', 'v1\Api\UserProfileApiController@postUploadImage');
    Route::put('users/update-license', 'v1\Api\UserProfileApiController@putUpdateLicense');
    Route::post('users/work-experience-save', 'v1\Api\WorkExperienceApiController@postWorkExperience');
    Route::delete('users/work-experience-delete', 'v1\Api\WorkExperienceApiController@deleteWorkExperience');
    Route::post('users/work-experience-list', 'v1\Api\WorkExperienceApiController@postListWorkExperience');
    Route::get('users/school-list', 'v1\Api\WorkExperienceApiController@getSchoolList');
    Route::post('users/school-add', 'v1\Api\WorkExperienceApiController@postSchoolSaveUpdate');
    Route::get('users/affiliation-list', 'v1\Api\AffiliationsApiController@getAffiliationList');
    Route::post('users/affiliation-save', 'v1\Api\AffiliationsApiController@postAffiliationSaveUpdate');
    Route::post('users/about-me-save', 'v1\Api\UserProfileApiController@postAboutMe');
    Route::get('users/about-me-list', 'v1\Api\UserProfileApiController@getAboutMe');


    Route::get('users/search-job', 'v1\Api\SkillApiController@getJobSearch');
    Route::get('users/user-profile', 'v1\Api\UserProfileApiController@getUserProfile');
    Route::put('users/user-profile-update', 'v1\Api\UserProfileApiController@updateUserProfile');
    Route::post('users/user-location-update', 'v1\Api\UserProfileApiController@updateUserLocationUpdate');

    Route::post('users/acceptreject-job', 'v1\Api\SearchApiController@postAcceptRejectInvitedJob');



    Route::post('users/update-availability', 'v1\Api\CalendarApiController@postJobAvailability');
    Route::post('users/search-jobs', 'v1\Api\SearchApiController@postSearchjobs');
    Route::get('users/chat-user-list', 'v1\Api\UserApiController@chatRecruiterList');
    Route::post('users/chat-user-block-unblock', 'v1\Api\UserApiController@chatBlockUnblockRecruiter');
    Route::post('users/save-job', 'v1\Api\SearchApiController@postSaveUnsavejob');
    Route::post('users/apply-job', 'v1\Api\SearchApiController@postApplyJob');
    Route::post('users/cancel-job', 'v1\Api\SearchApiController@postCancelJob');
    Route::get('users/job-list', 'v1\Api\SearchApiController@getJobList');
    Route::get('users/jobs', 'v1\Api\SearchApiController@getJobList1');
    Route::post('jobs/job-detail', 'v1\Api\SearchApiController@postJobDetail');
    Route::post('jobs/hired-jobs', 'v1\Api\CalendarApiController@postHiredJobsByDate');

    Route::post('users/availability-list', 'v1\Api\CalendarApiController@postAvailability');

    Route::get('users/notification-list', 'v1\Api\PushNotificationApiController@getNotificationlists');
    Route::post('users/notification-read', 'v1\Api\PushNotificationApiController@PostUpdateNotification');
    Route::get('users/unread-notification', 'v1\Api\PushNotificationApiController@GetunreadNotification');
    Route::post('users/delete-notification', 'v1\Api\PushNotificationApiController@PostDeleteNotification');

    Route::post('chat/send-message', 'v1\Api\PushNotificationApiController@userChatNotification');
    Route::post('users/update-devicetoken', 'v1\Api\PushNotificationApiController@PostUpdateDeviceToken');

    Route::post('admin/forgot-password', 'v1\Api\UserApiController@postAdminForgotPassword');
    
    Route::get('jobs/preferred-job-locations', 'v1\Api\MasterApiController@getPreferrefJobLocation');
       
});
