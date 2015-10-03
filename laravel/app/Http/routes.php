<?php
//use Input;


Route::get('about', 'PagesController@about');


Route::group(['prefix' => 'api'], function () {
    
    Route::post('save', ['as' => 'api.save', 'uses' => 'ApiController@save']);    
    Route::post('load', ['as' => 'api.load', 'uses' => 'ApiController@load']);
    
});