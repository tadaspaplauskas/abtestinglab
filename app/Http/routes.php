<?php
//use Input;


Route::get('about', 'PagesController@about');


Route::group(['prefix' => 'api', ], function () {
    
    //secured
    Route::group(['middleware' => 'verifyAPIToken'], function () {
        Route::post('save', 'API\TestController@storeTests');    
        Route::post('load', 'API\TestController@loadTests');
        Route::any('save_conversion', 'API\ConversionController@saveConversion');
    });
    
    //public
    Route::any('new_visitor', 'API\VisitorController@newVisitor');

});

//Route::group(['prefix' => 'user'], function () {
Route::group(['middleware' => 'auth'], function ()
{
    Route::get('dashboard', 'UserController@index');
    
    Route::group(['prefix' => 'website'], function ()
    {
        Route::get('index', 'WebsiteController@index');
        Route::get('show/{id}', 'WebsiteController@show');
        Route::get('show/archived/{id}', 'WebsiteController@showArchived');
        Route::get('create', 'WebsiteController@create');
        Route::post('store', 'WebsiteController@store');        
        Route::get('edit/{id}', 'WebsiteController@edit');    
        Route::post('update', 'WebsiteController@update');
        Route::get('delete/{id}', 'WebsiteController@delete');
        Route::post('destroy', 'WebsiteController@destroy');
        Route::get('enable/{id}', 'WebsiteController@enable');
        Route::get('disable/{id}', 'WebsiteController@disable');
    });
    
    Route::group(['prefix' => 'tests'], function ()
    {
        Route::get('disable/{id}', 'TestController@changePublicStatus');
        Route::get('enable/{id}', 'TestController@changePublicStatus');
        Route::get('publish/{id}', 'TestController@publish');
        Route::get('manager/{id}', 'TestController@manager');        
        Route::get('archive/{id}', 'TestController@changeArchiveStatus');
        Route::get('delete/{id}', 'TestController@delete');
        Route::post('destroy', 'TestController@destroy');
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