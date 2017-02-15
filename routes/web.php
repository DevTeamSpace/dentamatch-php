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

Route::post('login', 'web\SignupController@postLogin');
Route::get('login', 'web\SignupController@getLogin');
Route::get('verification-code/{code}', 'web\SignupController@getVerificationCode');
Route::get('user-activation/{code}', 'Api\UserApiController@getActivatejobseeker');
Route::get('logout', 'web\SignupController@logout');

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
            Route::post('job/updateStatus', 'web\RecruiterJobController@updateStatus');
            Route::get('job/seekerdetails/{seekerId}/{jobId}', 'web\RecruiterJobController@jobSeekerDetails');
            Route::post('createJob/saveOrUpdate', 'web\RecruiterJobController@saveOrUpdate');
            Route::post('create-profile', 'web\UserProfileController@createProfile');
            Route::post('office-details', 'web\UserProfileController@officeDetails');
            Route::get('get-location/{zip}', 'web\UserProfileController@checkValidLocation');

            Route::get('favorite-jobseeker','web\FavoriteJobseekerController@getFavJobseeker');
            Route::post('invite-jobseeker','web\FavoriteJobseekerController@postInviteJobseeker');
            Route::get('edit-profile', 'web\UserProfileController@getEditProfile');
            Route::get('subscription-detail', 'web\SubscriptionController@getSubscription');
            Route::get('get-subscription-list', 'web\SubscriptionController@getSubscriptionList');
            Route::post('create-subscription', 'web\SubscriptionController@postCreateSubscription');
            Route::get('setting-subscription', 'web\SubscriptionController@getSettingSubscription');
            Route::post('unsubscribe', 'web\SubscriptionController@postUnsubscribe');
            Route::get('get-subscription-details', 'web\SubscriptionController@getSubscriptionDetails');
            Route::post('add-card', 'web\SubscriptionController@postAddCard');
            Route::post('delete-card', 'web\SubscriptionController@postDeleteCard');
            Route::post('edit-card', 'web\SubscriptionController@postEditCard');
            Route::get('change-password', 'web\UserProfileController@getChangePassword');
            Route::post('change-password', 'web\UserProfileController@postChangePassword');
        });

        Route::group(['middleware' => 'termCondition'], function () {
            Route::get('terms-conditions', 'web\SignupController@getTermsAndCondition');
            Route::get('tutorial', 'web\SignupController@getTutorial');
        });
    });
});

Route::group(['middleware' => ['web', 'xss'], 'prefix' => 'cms/'], function () {

    Route::get('login', 'Auth\LoginController@getLogin');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/', 'Cms\LocationController@index');
    Route::get('/home', 'cms\LocationController@index');
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
        Route::get('list', 'Cms\JobSeekerController@jobSeekerList');
        Route::delete('{id}/delete', 'Cms\JobSeekerController@delete');
        Route::get('{id}/edit', 'Cms\JobSeekerController@edit');
        Route::get('create', 'Cms\JobSeekerController@create');
        Route::post('store', 'Cms\JobSeekerController@store');
    });
});

