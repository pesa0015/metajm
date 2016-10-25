<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'IndexController@welcome');

Route::post('get-services', 'CompanyController@getServices');

Route::group(['prefix' => 'search'], function() {
	Route::post('category', 'SearchController@category');
	Route::post('address', 'SearchController@getCompanies');
	Route::post('live-search', 'SearchController@liveSearch');
	Route::post('main-search', 'SearchController@mainSearch');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
	Route::auth();

	Route::get('logga-in/privat', 'LoginController@loginPrivate');
	Route::get('logga-in/foretag', 'LoginController@loginCompany');
	Route::post('auth/private', 'LoginController@authPrivate');
	Route::post('auth/company', 'LoginController@authCompany');
    Route::get('logout', 'LoginController@logout');

	Route::get('company/start', 'EmployerController@start');
	Route::get('company/services', 'EmployerController@showServices');
	Route::get('company/opening_hours', 'EmployerController@showOpeningHours');
	Route::post('set-opening-hours', 'EmployerController@setOpeninghours');

    Route::group(['prefix' => 'company/services'], function() {
	    Route::post('create', 'ServiceController@create');
	    Route::post('edit', 'ServiceController@edit');
	    Route::post('use', 'ServiceController@useService');
	});
});
