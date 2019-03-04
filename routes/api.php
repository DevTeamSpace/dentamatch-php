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

    /**
     * Authentication
     */
    Route::post('users/sign-up','Api\v1\UserApiController@postSignup');
    Route::post('users/sign-in','Api\v1\UserApiController@postSignIn');
    Route::delete('users/sign-out','Api\v1\UserApiController@deleteSignOut');
    Route::put('users/forgot-password', 'Api\v1\UserApiController@putForgotPassword');
    Route::put('users/reset-password', 'Api\v1\UserApiController@putForgotPassword');

    /**
     * Terms & Conditions
     */
    Route::get('privacy-policy','Api\v1\MasterApiController@getPrivacyPolicy');
    Route::get('term-condition','Api\v1\MasterApiController@getTermsAndCondition');

    /**
     * User Profile
     */
    Route::post('users/change-password','Api\v1\UserProfileApiController@postChangePassword');
    Route::get('users/is-verified','Api\v1\UserProfileApiController@getIsUserVerified');
    Route::post('users/upload-image', 'Api\v1\UserProfileApiController@postUploadImage');
    Route::put('users/update-license', 'Api\v1\UserProfileApiController@putUpdateLicense');
    Route::post('users/about-me-save', 'Api\v1\UserProfileApiController@postAboutMe');
    Route::get('users/about-me-list', 'Api\v1\UserProfileApiController@getAboutMe');
    Route::get('users/user-profile', 'Api\v1\UserProfileApiController@getUserProfile');
    Route::put('users/user-profile-update', 'Api\v1\UserProfileApiController@updateUserProfile');
    Route::post('users/user-location-update', 'Api\v1\UserProfileApiController@updateUserLocationUpdate');

    /**
     * JobSeeker's Skills
     */
    Route::get('list-skills','Api\v1\SkillApiController@getSkills');
    Route::post('users/update-skill','Api\v1\SkillApiController@postSkills');

    /**
     * JobSeeker's Certificates
     */
    Route::get('list-certifications','Api\v1\CertificateApiController@getCertifications');
    Route::post('users/update-certificate','Api\v1\CertificateApiController@updateCertifications');
    Route::post('users/update-certificate-validity','Api\v1\CertificateApiController@updateCertificationsValidity');

    /**
     * JobSeeker's WorkExperience
     */
    Route::post('users/work-experience-list', 'Api\v1\WorkExperienceApiController@getWorkExperience');
    Route::post('users/work-experience-save', 'Api\v1\WorkExperienceApiController@postWorkExperience');
    Route::delete('users/work-experience-delete', 'Api\v1\WorkExperienceApiController@deleteWorkExperience');

    /**
     * JobSeeker's Schooling Data
     */
    Route::get('users/school-list', 'Api\v1\SchoolingApiController@getSchoolList');
    Route::post('users/school-add', 'Api\v1\SchoolingApiController@postSchoolSaveUpdate');

    /**
     *  JobSeeker Affiliations
     */
    Route::get('users/affiliation-list', 'Api\v1\AffiliationsApiController@getAffiliationList');
    Route::post('users/affiliation-save', 'Api\v1\AffiliationsApiController@postAffiliationSaveUpdate');

    /**
     *  JobSeeker Calendar & Available Dates
     */
    Route::post('users/update-availability', 'Api\v1\CalendarApiController@postAvailabilityDates');
    Route::post('jobs/hired-jobs', 'Api\v1\CalendarApiController@getHiredJobs');
    Route::post('users/availability-list', 'Api\v1\CalendarApiController@getAvailabilityDates');

    /**
     * Search for the job, apply & accept
     */
    Route::get('users/job-list', 'Api\v1\SearchApiController@getJobList');

    Route::post('users/search-jobs', 'Api\v1\SearchApiController@postSearchJobs');
    Route::post('jobs/job-detail', 'Api\v1\SearchApiController@postJobDetail');
    Route::post('users/save-job', 'Api\v1\SearchApiController@postSaveUnsaveJob');
    Route::post('users/apply-job', 'Api\v1\SearchApiController@postApplyJob');
    Route::post('users/cancel-job', 'Api\v1\SearchApiController@postCancelJob');
    Route::post('users/acceptreject-job', 'Api\v1\SearchApiController@postAcceptRejectInvitedJob');

    /**
     * JobSeeker's Push Notifications
     */
    Route::get('users/notification-list', 'Api\v1\PushNotificationApiController@getNotifications');
    Route::post('users/notification-read', 'Api\v1\PushNotificationApiController@updateNotification');
    Route::get('users/unread-notification', 'Api\v1\PushNotificationApiController@getUnreadCount');
    Route::post('users/delete-notification', 'Api\v1\PushNotificationApiController@PostDeleteNotification');
    Route::post('users/update-devicetoken', 'Api\v1\PushNotificationApiController@PostUpdateDeviceToken');

    /**
     * Send Push Notification from nodejs chat
     */
    Route::post('chat/send-message', 'Api\v1\PushNotificationApiController@userChatNotification');

    /**
     * JobSeeker's Chat
     */
    Route::get('users/chat-user-list', 'Api\v1\ChatController@getChatsWithRecruiters');
    Route::post('chat/init-chat', 'Api\v1\ChatController@postInitChatWithRecruiter');
    Route::post('chat/delete', 'Api\v1\PushNotificationApiController@userChatDelete');
    Route::post('users/chat-user-block-unblock', 'Api\v1\ChatController@chatBlockUnblockRecruiter');

    /**
     * Forgot password for Admin User
     */
    Route::post('admin/forgot-password', 'Api\v1\UserApiController@postAdminForgotPassword');

    /**
     *  Dictionary data
     */
    Route::get('list-states','Api\v1\MasterApiController@getStates');
    Route::get('jobs/preferred-job-locations', 'Api\v1\MasterApiController@getPreferredJobLocations');
    Route::get('list-jobtitle','Api\v1\MasterApiController@getJobTitles');

    /**
     * Some unused Stuff
     */
    Route::get('users/jobs', 'Api\v1\SearchApiController@getJobList1');  // todo no such method
    Route::get('users/search-job', 'Api\v1\SkillApiController@getJobSearch'); // todo no such method!
});
