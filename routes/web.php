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
            Route::get('job/details/{jobId}', 'web\RecruiterJobController@jobDetails');
            Route::post('job/updateStatus', 'web\RecruiterJobController@updateStatus');
            Route::post('createJob/saveOrUpdate', 'web\RecruiterJobController@saveOrUpdate');
            Route::post('create-profile', 'web\UserProfileController@createProfile');
            Route::post('office-details', 'web\UserProfileController@officeDetails');
            Route::get('get-location/{zip}', 'web\UserProfileController@checkValidLocation');

            Route::get('favorite-jobseeker','web\FavoriteJobseekerController@getFavJobseeker');
            Route::post('invite-jobseeker','web\FavoriteJobseekerController@postInviteJobseeker');
            Route::get('edit-profile', 'web\UserProfileController@getEditProfile');
            Route::get('subscription-detail', 'web\SubscriptionController@getSubscription');
            Route::get('get-subscription-list', 'web\SubscriptionController@getSubscriptionList');
            Route::get('stripe/connect', 'web\SubscriptionController@getStripeConnect');
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
});

