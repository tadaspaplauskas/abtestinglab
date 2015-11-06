<?php

Route::get('about', 'PagesController@about');

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
    
    Route::group(['prefix' => 'website'], function ()
    {
        Route::get('index', ['as' => 'website.index', 'uses' => 'WebsiteController@index']);
        Route::get('show/{id}', ['as' => 'website.show', 'uses' => 'WebsiteController@show']);
        Route::get('show/archived/{id}', ['as' => 'website.archived', 'uses' => 'WebsiteController@showArchived']);
        Route::get('create', ['as' => 'website.create', 'uses' => 'WebsiteController@create']);
        Route::post('store', ['as' => 'website.store', 'uses' => 'WebsiteController@store']);        
        Route::get('edit/{id}', ['as' => 'website.edit', 'uses' => 'WebsiteController@edit']);    
        Route::post('update', ['as' => 'website.update', 'uses' => 'WebsiteController@update']);
        Route::get('delete/{id}', ['as' => 'website.delete', 'uses' => 'WebsiteController@delete']);
        Route::post('destroy', ['as' => 'website.destroy', 'uses' => 'WebsiteController@destroy']);
        Route::get('enable/{id}', ['as' => 'website.enable', 'uses' => 'WebsiteController@enable']);
        Route::get('disable/{id}', ['as' => 'website.disable', 'uses' => 'WebsiteController@disable']);
    });
    
    Route::group(['prefix' => 'tests'], function ()
    {
        //Route::get('disable/{id}', ['as' => 'tests.disable', 'uses' => 'TestController@changePublicStatus']);
        //Route::get('enable/{id}', ['as' => 'tests.enable', 'uses' => 'TestController@changePublicStatus']);
        Route::get('publish/{id}', ['as' => 'tests.publish', 'uses' => 'TestController@publish']);
        Route::get('manager/{id}', ['as' => 'tests.manager', 'uses' => 'TestController@manager']);        
        Route::get('archive/{id}', ['as' => 'tests.archive', 'uses' => 'TestController@changeArchiveStatus']);
        Route::get('delete/{id}', ['as' => 'tests.delete', 'uses' => 'TestController@delete']);
        Route::post('destroy', ['as' => 'tests.destroy', 'uses' => 'TestController@destroy']);
    });
});


//auth stuff
Route::get('auth/login', 'Auth\AuthController@login');
Route::get('auth/register', 'Auth\AuthController@register');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

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