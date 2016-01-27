<?php

Route::group(['prefix' => 'api', ], function () {

    //secured
    Route::group(['middleware' => 'verifyAPIToken'], function () {
        Route::post('save', 'API\TestController@storeTests');
        Route::post('load', 'API\TestController@loadTests');
    });

    //public
    Route::any('save_conversion', 'API\ConversionController@saveConversion');
    Route::any('new_visitor', 'API\VisitorController@newVisitor');
    Route::any('log_visit', 'API\VisitorController@logVisit');
});


//Route::group(['prefix' => 'user'], function () {
Route::group(['middleware' => 'auth'], function ()
{
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
    Route::get('account', ['as' => 'account', 'uses' => 'UserController@edit']);
    Route::get('payments', ['as' => 'payments', 'uses' => 'PaymentController@index']);
    Route::post('update', ['as' => 'user.update', 'uses' => 'UserController@update']);

    Route::group(['prefix' => 'websites'], function ()
    {
        Route::get('manager_redirect/{url}', ['as' => 'websites.manager.redirect', 'uses' => 'WebsiteController@managerRedirect']);

        Route::get('archived/{id}', ['as' => 'websites.archived', 'uses' => 'WebsiteController@showArchived']);
        Route::get('create', ['as' => 'websites.create', 'uses' => 'WebsiteController@create']);
        Route::get('{owned_website}/instructions', ['as' => 'websites.instructions', 'uses' => 'WebsiteController@installInstructions']);
        Route::post('{owned_website}/send_instructions', ['as' => 'websites.send_instructions', 'uses' => 'WebsiteController@sendInstructions']);
        Route::get('edit/{id}', ['as' => 'websites.edit', 'uses' => 'WebsiteController@edit']);
        Route::post('update', ['as' => 'websites.update', 'uses' => 'WebsiteController@update']);
        Route::get('delete/{id}', ['as' => 'websites.delete', 'uses' => 'WebsiteController@delete']);
        Route::post('destroy', ['as' => 'websites.destroy', 'uses' => 'WebsiteController@destroy']);
        //Route::get('enable/{id}', ['as' => 'websites.enable', 'uses' => 'WebsiteController@enable']);
        //Route::get('disable/{id}', ['as' => 'websites.disable', 'uses' => 'WebsiteController@disable']);
        Route::get('stop/{id}', ['as' => 'websites.stop', 'uses' => 'WebsiteController@stopAllTesting']);

        Route::get('', ['as' => 'websites.index', 'uses' => 'WebsiteController@index']);
        Route::get('{id}', ['as' => 'websites.show', 'uses' => 'WebsiteController@show']);

        Route::group(['middleware' => 'checkResources'], function()
        {
            Route::get('create', ['as' => 'websites.create', 'uses' => 'WebsiteController@create']);
            Route::get('edit/{id}', ['as' => 'websites.edit', 'uses' => 'WebsiteController@edit']);
            Route::post('update', ['as' => 'websites.update', 'uses' => 'WebsiteController@update']);
            Route::post('', ['as' => 'websites.store', 'uses' => 'WebsiteController@store']);
        });
    });

    Route::group(['prefix' => 'tests'], function ()
    {
        Route::get('archive/{id}', ['as' => 'tests.archive', 'uses' => 'TestController@changeArchiveStatus']);
        //Route::get('delete/{id}', ['as' => 'tests.delete', 'uses' => 'TestController@delete']);

        Route::group(['middleware' => 'checkResources'], function()
        {
            Route::get('destroy/{id}', ['as' => 'tests.destroy', 'uses' => 'TestController@destroy']);
            Route::get('disable/{id}', ['as' => 'tests.disable', 'uses' => 'TestController@changePublicStatus']);
            Route::get('enable/{id}', ['as' => 'tests.enable', 'uses' => 'TestController@changePublicStatus']);
            Route::get('publish/{id}', ['as' => 'tests.publish', 'uses' => 'TestController@publish']);


            Route::get('manager/exit/{id}', ['as' => 'tests.manager.exit', 'uses' => 'TestController@managerExit']);
            Route::get('manager/{owned_website}', ['as' => 'tests.manager', 'uses' => 'TestController@manager']);
        });

    });
});


//auth stuff
Route::get('login', ['uses' => 'Auth\AuthController@login', 'as' => 'login']);
Route::get('register', ['uses' => 'Auth\AuthController@register', 'as' => 'register']);
Route::get('register/buy', ['uses' => 'Auth\AuthController@registerBuy', 'as' => 'register.buy']);
Route::post('register', ['uses' => 'Auth\AuthController@postRegister', 'as' => 'registerPOST']);
Route::post('login', ['uses' => 'Auth\AuthController@postLogin', 'as' => 'loginPOST']);
Route::get('logout', ['uses' => 'Auth\AuthController@getLogout', 'as' => 'logout']);

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Social logins...
Route::get('auth/google', 'Auth\AuthController@redirectToProviderGoogle');
Route::get('auth/google/callback', 'Auth\AuthController@handleProviderCallbackGoogle');

Route::get('auth/facebook', 'Auth\AuthController@redirectToProviderFacebook');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallbackFacebook');

Route::group(['prefix' => 'payments', ], function () {
    Route::get('cancel', 'PaymentController@cancel');
    Route::get('success', 'PaymentController@success');

    Route::any('received_paypal', 'PaymentController@receivedPaypal');
});

Route::get('/', ['as' => 'index', 'uses' => 'PagesController@index']);
Route::get('about', ['as' => 'about', 'uses' => 'PagesController@about']);
Route::get('help', ['as' => 'help', 'uses' => 'PagesController@about']);
Route::get('faq', ['as' => 'faq', 'uses' => 'PagesController@faq']);
Route::get('contact', ['as' => 'contact', 'uses' => 'PagesController@about']);
Route::get('pricing', ['as' => 'pricing', 'uses' => 'PagesController@pricing']);


/* only for testing */
Route::get('/test_user_created', function() {
    event(new \App\Events\TestUserCreated('tester@abtestinglab.com'));
});
Route::get('/test_end', function() {
    event(new \App\Events\TestsEnded('tester@abtestinglab.com'));
});

Route::get('/test_conversions_check', function () {
    $user = \App\User::where('email', 'tester@abtestinglab.com')->first();

    $website = $user->websites()->first();

    $totalConversions = 0;
    $totalViews = 0;

    foreach ($website->tests as $test)
    {
        $totalConversions += $test->original_conversion_count + $test->variation_conversion_count;
        $totalViews += $test->original_pageviews + $test->variation_pageviews;

        if (!$test->conversions()->exists())
            return 'no conversion logged for test ' . $test->id;
    }

    $check = ($totalConversions > 0 && $totalViews === $totalConversions);

    return $check ? 'pass' : $totalConversions . ' : ' . $totalViews;
});