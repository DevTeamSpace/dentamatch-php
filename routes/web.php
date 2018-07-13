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
  Route::post('stripe-test', 'web\SubscriptionController@getStripeTest');
  Route::get('image/{w}/{h}/', function(League\Glide\Server $server, $w, $h) {
    $server->outputImage($_GET['src'], ['w' => $w, 'h' => $h, 'fit' => 'crop']);
    });
  Route::get('/', 'web\SignupController@getLogin');
 
  Route::get('signup', 'web\SignupController@getLogin');
  Route::post('signup', 'web\SignupController@postSignUp');
  Route::post('jobseeker/storeSignup', 'web\SignupController@postJobseekerSignUp');
  Route::get('jobseeker/signup', 'web\SignupController@getJobseekerSignUp');

  Route::post('login', 'web\SignupController@postLogin');
  Route::get('login', 'web\SignupController@getLogin');
  Route::get('verification-code/{code}', 'web\SignupController@getVerificationCode');
  Route::get('user-activation/{code}', 'Api\v1\UserApiController@getActivatejobseeker');
  Route::get('logout', 'web\SignupController@logout');
  Route::get('users/notification/seen/{id}', 'web\NotificationController@seenNotification');
  Route::get('/aboutus', function () {
    return view('about');
});
  Route::get('/success-register', function () {
    return view('auth.passwords.successfully_reg');
});
  Route::get('/success-active', function () {
    return view('auth.passwords.successfully_active');
});

  Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
  Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
  Route::post('password/reset', 'Auth\ResetPasswordController@reset');
  Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
  

  Route::group(['middleware' => ['auth', 'xss', 'nocache']], function () {
    Route::group(['middleware' => 'recruiter'], function () {
        Route::group(['middleware' => 'acceptedTerms'], function () {
            Route::get('home', 'web\SignupController@dashboard')->middleware('officeDetails');
            Route::post('create-profile', 'web\UserProfileController@createProfile');
            Route::post('office-details', 'web\UserProfileController@officeDetails');
            Route::get('get-location/{zip}', 'web\UserProfileController@checkValidLocation');
            Route::get('edit-profile', 'web\UserProfileController@getEditProfile');
            Route::get('setting-terms-conditions', 'web\UserProfileController@getTermsConditions');
            Route::get('setting-privacy-policy', 'web\UserProfileController@getPrivacyPolicy');
            Route::get('change-password', 'web\UserProfileController@getChangePassword');
            Route::post('change-password', 'web\UserProfileController@postChangePassword');
            Route::group(['middleware' => 'subscription'], function () {
                Route::get('dashboard', 'web\UserProfileController@dashboard');
                Route::get('jobtemplates', 'web\JobtemplateController@listJobTemplates');
                Route::get('jobtemplates/view/{templateId}', 'web\JobtemplateController@viewTemplate');
                Route::get('jobtemplates/edit/{templateId}', 'web\JobtemplateController@editJobTemplate');
                Route::delete('jobtemplates/delete', 'web\JobtemplateController@deleteJobTemplate');
                Route::get('jobtemplates/create', 'web\JobtemplateController@createJobTemplate');
                Route::post('jobtemplates/saveOrUpdate', 'web\JobtemplateController@saveOrUpdate');
                Route::get('createJob/{templateId}', 'web\RecruiterJobController@createJob');
                Route::get('job/lists', 'web\RecruiterJobController@listJobs');
                Route::get('job/search/{jobId}', 'web\RecruiterJobController@searchSeekers');
                Route::get('job/details/{jobId}', 'web\RecruiterJobController@jobDetails');
                Route::get('job/details/{jobId}/{appliedStatus}', 'web\RecruiterJobController@getJobSeekerDetails');
                Route::get('job/edit/{jobId}', 'web\RecruiterJobController@jobEdit');
                Route::get('job/edit-details', 'web\RecruiterJobController@jobEditDetails');
                Route::post('edit-job', 'web\RecruiterJobController@postEditJob');
                Route::post('delete-job', 'web\RecruiterJobController@postDeleteJob');
                Route::post('job/updateStatus', 'web\RecruiterJobController@updateStatus');
                Route::get('job/seekerdetails/{seekerId}/{jobId}', 'web\RecruiterJobController@jobSeekerDetails');
                Route::get('/jobseeker/{seekerId}', 'web\RecruiterJobController@jobSeekerProfile');
                Route::post('createJob/saveOrUpdate', 'web\RecruiterJobController@saveOrUpdate');
                                
                Route::get('favorite-jobseeker','web\FavoriteJobseekerController@getFavJobseeker');
                Route::get('get-favorite-job-lists','web\FavoriteJobseekerController@postFavouriteJobList');
                Route::post('invite-jobseeker','web\FavoriteJobseekerController@postInviteJobseeker');              
                Route::get('edit-profile', 'web\UserProfileController@getEditProfile');
                Route::get('recruiter-profile-details', 'web\UserProfileController@getRecruiterProfileDetails');
                Route::post('update-recruiter-info', 'web\UserProfileController@postUpdateRecruiterProfile');

                Route::get('setting-terms-conditions', 'web\UserProfileController@getTermsConditions');
                Route::get('change-password', 'web\UserProfileController@getChangePassword');
                Route::post('change-password', 'web\UserProfileController@postChangePassword');
                Route::get('chat', 'web\ChatController@getChatSeekerList');
                Route::get('calender', 'web\CalenderController@getCalender');
                Route::get('calender-details', 'web\CalenderController@getCalenderDetails');
                Route::get('calender-seeker-details', 'web\CalenderController@getCalenderSeekers');
                Route::get('reports', 'web\ReportsController@getReportsPage');
                Route::get('reports-temp-jobs', 'web\ReportsController@getReportsTempJobs');
                Route::get('report-seekers', 'web\ReportsController@getReportSeekers');
                Route::get('individual-temp-job', 'web\ReportsController@getIndividualTempJob');
                Route::get('job-applied-or-not', 'web\RecruiterJobController@appliedOrNot');
                Route::post('edit-recruiter-office', 'web\UserProfileController@postEditRecruiterOffice');
                Route::post('delete-office', 'web\UserProfileController@postDeleteOffice');
                Route::get('notification-lists', 'web\NotificationController@getNotificationList');
                Route::get('{id}/delete-notification', 'web\NotificationController@deleteNotification');
                Route::get('checknotratedjobs', 'web\RatingController@getRating');
                Route::get('users/dashboard', 'web\RecruiterJobController@dashboard');
            });
            Route::get('home', 'web\SignupController@dashboard')->middleware('officeDetails');
            Route::get('/get-location/{zip}', 'web\UserProfileController@checkValidLocation');
            Route::post('create-profile', 'web\UserProfileController@createProfile');
            Route::get('subscription-detail', 'web\SubscriptionController@getSubscription');
            Route::get('get-subscription-list', 'web\SubscriptionController@getSubscriptionList');
            Route::post('create-subscription', 'web\SubscriptionController@postCreateSubscription');
            Route::get('setting-subscription', 'web\SubscriptionController@getSettingSubscription');
            Route::post('unsubscribe', 'web\SubscriptionController@postUnsubscribe');
            Route::post('subscribe-again', 'web\SubscriptionController@postSubscribeAgain');
            Route::get('get-subscription-details', 'web\SubscriptionController@getSubscriptionDetails');
            Route::post('change-subscription-plan', 'web\SubscriptionController@postChangeSubscriptionPlan');
            Route::post('add-card', 'web\SubscriptionController@postAddCard');
            Route::post('delete-card', 'web\SubscriptionController@postDeleteCard');
            Route::post('edit-card', 'web\SubscriptionController@postEditCard');
            Route::get('setting-terms-conditions', 'web\UserProfileController@getTermsConditions');
            Route::get('change-password', 'web\UserProfileController@getChangePassword');
            Route::post('change-password', 'web\UserProfileController@postChangePassword');
            Route::get('get-plans','web\SubscriptionController@getPlans');

            //Route::get('chat', 'web\ChatController@getChatSeekerList');
            Route::get('calender', 'web\CalenderController@getCalender');
            Route::get('calender-details', 'web\CalenderController@getCalenderDetails');
            Route::get('recruiter/markFavourite/{seekerId}', 'web\FavoriteJobseekerController@getMarkFavourite');
            Route::post('recruiter/rating', 'web\RatingController@createOrUpdate');
        });

Route::group(['middleware' => 'termCondition'], function () {
    Route::get('terms-conditions', 'web\SignupController@getTermsAndCondition');
    Route::get('tutorial', 'web\SignupController@getTutorial')->middleware('officeDetails');;
});
});
});

Route::group(['middleware' => ['web', 'xss', 'nocache'], 'prefix' => 'cms/'], function () {

    Route::get('login', 'Auth\LoginController@getLogin');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/', 'Cms\LocationController@index');
    Route::get('/home', 'cms\LocationController@index');
    Route::group(['prefix' => 'user/'], function() {
        Route::get('listPhotographer', 'Cms\UserController@getPhotographerList');
        Route::get('listConsumer', 'Cms\UserController@getConsumerList');
        Route::get('listDesigner', 'Cms\UserController@getDesignerList');
        Route::get('searchDesignerList', 'Cms\UserController@searchDesignerList');
        Route::get('create', 'Cms\UserController@createUser');
        Route::post('store', 'Cms\UserController@store');
        Route::post('reject', 'Cms\UserController@reject');
        Route::post('deactivate', 'Cms\UserController@deactivate');
        Route::get('{id}/view', 'Cms\UserController@view');
        Route::get('{id}/activeOrInactive', 'Cms\UserController@updateActiveStatus');
        Route::get('index', 'Cms\UserController@index');
        Route::get('', 'Cms\UserController@index');
        Route::get('changePassword', 'Cms\UserController@changePassword');
        Route::post('updatePassword', 'Cms\UserController@updatePassword');
    });

    Route::group(['prefix' => 'location/'], function() {
        Route::get('index', 'Cms\LocationController@index');
        Route::get('list', 'Cms\LocationController@locationsList');
        Route::delete('{id}/delete', 'Cms\LocationController@delete');
        Route::get('{id}/edit', 'Cms\LocationController@edit');
        Route::get('create', 'Cms\LocationController@create');
        Route::post('store', 'Cms\LocationController@store');
    });
    
    Route::group(['prefix' => 'affiliation/'], function() {
        Route::get('index', 'Cms\AffiliationController@index');
        Route::get('list', 'Cms\AffiliationController@affiliationsList');
        Route::delete('{id}/delete', 'Cms\AffiliationController@delete');
        Route::get('{id}/edit', 'Cms\AffiliationController@edit');
        Route::get('create', 'Cms\AffiliationController@create');
        Route::post('store', 'Cms\AffiliationController@store');
    });
    
    Route::group(['prefix' => 'jobtitle/'], function() {
        Route::get('index', 'Cms\JobTitleController@index');
        Route::get('list', 'Cms\JobTitleController@jobTitleList');
        Route::delete('{id}/delete', 'Cms\JobTitleController@delete');
        Route::get('{id}/edit', 'Cms\JobTitleController@edit');
        Route::get('create', 'Cms\JobTitleController@create');
        Route::post('store', 'Cms\JobTitleController@store');
    });
    
    Route::group(['prefix' => 'officetype/'], function() {
        Route::get('index', 'Cms\OfficeTypeController@index');
        Route::get('list', 'Cms\OfficeTypeController@officeTypeList');
        Route::delete('{id}/delete', 'Cms\OfficeTypeController@delete');
        Route::get('{id}/edit', 'Cms\OfficeTypeController@edit');
        Route::get('create', 'Cms\OfficeTypeController@create');
        Route::post('store', 'Cms\OfficeTypeController@store');
    });
    
    Route::group(['prefix' => 'certificate/'], function() {
        Route::get('index', 'Cms\CertificateController@index');
        Route::get('list', 'Cms\CertificateController@certificationList');
        Route::delete('{id}/delete', 'Cms\CertificateController@delete');
        Route::get('{id}/edit', 'Cms\CertificateController@edit');
        Route::get('create', 'Cms\CertificateController@create');
        Route::post('store', 'Cms\CertificateController@store');
    });
    
    Route::group(['prefix' => 'config/'], function() {
        Route::get('create-radius', 'Cms\ConfigurationController@create');
        Route::post('store-radius', 'Cms\ConfigurationController@store');
    });
    
    Route::group(['prefix' => 'skill/'], function() {
        Route::get('index', 'Cms\SkillController@index');
        Route::get('list', 'Cms\SkillController@skillList');
        Route::delete('{id}/delete', 'Cms\SkillController@delete');
        Route::get('{id}/edit', 'Cms\SkillController@edit');
        Route::get('create', 'Cms\SkillController@create');
        Route::post('store', 'Cms\SkillController@store');
    });
    
    Route::group(['prefix' => 'school/'], function() {
        Route::get('index', 'Cms\SchoolController@index');
        Route::get('list', 'Cms\SchoolController@schoolList');
        Route::delete('{id}/delete', 'Cms\SchoolController@delete');
        Route::get('{id}/edit', 'Cms\SchoolController@edit');
        Route::get('create', 'Cms\SchoolController@create');
        Route::post('store', 'Cms\SchoolController@store');
    });
    
    Route::group(['prefix' => 'jobseeker/'], function() {
        Route::get('index', 'Cms\JobSeekerController@index');
        Route::get('verification', 'Cms\JobSeekerController@verificationLicense');
        Route::get('list', 'Cms\JobSeekerController@jobSeekerList');
        Route::delete('{id}/delete', 'Cms\JobSeekerController@delete');
        Route::get('{id}/edit', 'Cms\JobSeekerController@edit');
        Route::get('{id}/viewdetails', 'Cms\JobSeekerController@jobSeekerDetailView');
        Route::get('create', 'Cms\JobSeekerController@create');
        Route::post('store', 'Cms\JobSeekerController@store');
        Route::get('verification-list', 'Cms\JobSeekerController@jobSeekerVerificationList');
        Route::get('{id}/verification', 'Cms\JobSeekerController@jobSeekerVerificationView');
        Route::post('storeVerification', 'Cms\JobSeekerController@storeVerification');
        
        Route::get('unverified', 'Cms\JobSeekerController@unverified');
        Route::get('listUnverifiedJobseeker', 'Cms\JobSeekerController@unverifiedJobseekerList');
        Route::get('downloadUnverifiedJobseeker', 'Cms\JobSeekerController@downloadUnverifiedJobseekerCsv');
        
        Route::get('incomplete', 'Cms\JobSeekerController@incomplete');
        Route::get('listIncompleteJobseeker', 'Cms\JobSeekerController@incompleteJobseekerList');
        Route::get('downloadIncompleteJobseeker', 'Cms\JobSeekerController@downloadIncompleteJobseekerCsv');
        
        Route::get('nonavailableusers', 'Cms\JobSeekerController@nonAvailableUsers');
        Route::get('listNonAvailableUsers', 'Cms\JobSeekerController@listNonAvailableUsers');
        Route::get('downloadNonAvailableUsers', 'Cms\JobSeekerController@downloadNonAvailableUsersCsv');
        
        Route::get('invited', 'Cms\JobSeekerController@invited');
        Route::get('listInvitedUsers', 'Cms\JobSeekerController@listInvitedUsers');
        Route::get('downloadInvitedUsers', 'Cms\JobSeekerController@downloadInvitedUsersCsv');
    });
    
    Route::group(['prefix' => 'recruiter/'], function() {
        Route::get('index', 'Cms\RecruiterController@index');
        Route::get('list', 'Cms\RecruiterController@recruiterList');
        Route::get('{id}/delete', 'Cms\RecruiterController@delete');
        Route::get('{id}/edit', 'Cms\RecruiterController@edit');
        Route::get('create', 'Cms\RecruiterController@create');
        Route::post('store', 'Cms\RecruiterController@store');
        Route::post('storeAdminResetPassword', 'Cms\RecruiterController@storeAdminResetPassword');
        Route::get('{id}/adminResetPassword', 'Cms\RecruiterController@adminResetPassword');
        Route::get('{id}/view', 'Cms\RecruiterController@recruiterView');
    });
    
    Route::group(['prefix' => 'notify/'], function() {
        Route::get('index', 'Cms\AppMessageController@index');
        Route::get('list', 'Cms\AppMessageController@messageList');
        Route::get('{id}/delete', 'Cms\AppMessageController@delete');
        Route::get('{id}/send', 'Cms\AppMessageController@sendNotification');
        Route::get('{id}/edit', 'Cms\AppMessageController@edit');
        Route::get('create', 'Cms\AppMessageController@create');
        Route::post('store', 'Cms\AppMessageController@store');
    });
    Route::group(['prefix' => 'report/'], function() {
        Route::get('index', 'Cms\ReportController@index');
        Route::get('list', 'Cms\ReportController@jobLists');
        Route::get('{id}/view', 'Cms\ReportController@appliedSeekers');
        Route::get('seekerlist/{id}', 'Cms\ReportController@seekerList');
        Route::get('list', 'Cms\ReportController@jobLists');
        Route::get('cancellist', 'Cms\ReportController@cancelLists');
        Route::get('cancel', 'Cms\ReportController@listCancel');
        Route::get('responselist', 'Cms\ReportController@jobResponse');
        Route::get('response', 'Cms\ReportController@jobResponseList');
        Route::get('search-location', 'Cms\ReportController@searchJobByLocation');
        Route::get('location', 'Cms\ReportController@searchCountbyLocation');
        Route::get('download/{type}', 'Cms\ReportController@downloadCsv');
    });
    
    Route::get('push-notification', 'Cms\JobSeekerController@sendPushAndroid');
});

