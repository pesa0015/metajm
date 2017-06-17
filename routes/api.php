<?php

use Illuminate\Http\Request;

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

Route::post('booking/done', 'IndexController@check');

Route::group(['prefix' => 'get'], function () {
    Route::post('company', 'CompanyController@getCompany');
    Route::post('times', 'CompanyController@getTimesAndStylists');
    Route::post('hours', 'CompanyController@getHours');
    Route::post('days-available', 'CompanyController@getDays');
    Route::post('stylists', 'CompanyController@getStylists');
});

Route::group(['prefix' => 'search'], function () {
    Route::post('category', 'SearchController@category');
    Route::post('address', 'SearchController@getCompanies');
    Route::post('live-search', 'SearchController@liveSearch');
    Route::post('main-search', 'SearchController@mainSearch');
});