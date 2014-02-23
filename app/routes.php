<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	//return Redirect::route('overview.index');
	return Redirect::to(Session::get('loginRedirect', route('overview.index')));
});

Route::group(array('before' => 'auth.sentry.root'), function()
{
	// Group
	Route::resource('groups', 'GroupsController');
	
	// User
	Route::resource('users', 'UsersController');
});

Route::group(array('before' => 'auth.sentry'), function()
{
    // Overview
	Route::resource('overview', 'OverviewController', array('only' => array('index')));
	
	// Account
	Route::resource('accounts', 'AccountsController');

	// Log
	Route::resource('logs', 'LogsController', array('only' => array('index', 'show', 'destroy')));

	// Profile
	Route::resource('profile', 'ProfileController', array('only' => array('index')));
	
});

/*
|--------------------------------------------------------------------------
| Authentication and Authorization Routes
|--------------------------------------------------------------------------
|
|
|
*/

Route::group(array('prefix' => 'auth'), function()
{
	// Login
	Route::get('login', array('as' => 'auth.login', 'uses' => 'AuthController@getLogin'));
	Route::post('login', array('uses' => 'AuthController@postLogin'));
	
	// Logout
	Route::get('logout', array('as' => 'auth.logout', 'uses' => 'AuthController@getLogout'));
});

