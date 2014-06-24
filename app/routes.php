<?php

use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Groups\GroupNotFoundException;

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

// Route::get('ser', array('as' => 'ser', function()
// {
	//$a = unserialize(PanelLog::find(7)->description);
	//return var_dump($a->groups->lists('name'));
	
	// return Hash::make('123456');
// }));

Route::get('/uploadscript', function()
{
	$file = Input::get('file', '');
	
	if ($file and stripos(Request::header('User-Agent'), 'libcurl') !== false)
	{
		$ftpHome = Config::get('ftppanel.ftpHome');
		//$cdnDomain = Config::get('ftppanel.ftpDefaultDomain');
		$file = rtrim(urldecode($file), '/');
		$relativeFile = str_replace($ftpHome, '', $file);
		$topDir = explode('/', trim($relativeFile, '/'));
		$topDir = $ftpHome.'/'.$topDir[0];
		$aliases = array();
		$txtContent = '';
		
		$accountsWithSameTopLevelDir = Account::where('home', 'LIKE', $topDir.'%');
		
		$accountsWithSameTopLevelDir->get()->each(function($account) use (&$aliases)
		{
			$aliases = array_merge($aliases, $account->aliases()->lists('domain'));
		});
		
		$aliases = array_unique($aliases);
		
		foreach ($aliases as $alias)
		{
			$txtContent .= 'http://'.$alias.'/'.encodeURI($relativeFile)."\r\n";
		}
		
		shell_exec(sprintf('echo "%s" | sudo tee %s', $txtContent, $file.'.txt'));
		
		$sharedHome = $accountsWithSameTopLevelDir->where('readonly', '=', 1)->lists('username');
		
		if ( ! empty($sharedHome))
		{
			Event::fire('account.readonly_upload', array($topDir));
		}
	}
	
	return;
})->where('file', '.*');

Route::get('keepalive', array('as' => 'keepalive', function()
{
	return;
}));

Route::group(array('before' => 'auth.sentry.root'), function()
{
	// Group
	Route::bind('groups', function($id, $route) {
		// Disallow edit, update and delete root group
		if ($route->getName() != 'groups.show' and ($id == 1 or  in_array($id, Sentry::getUser()->getGroups()->lists('id'))))
		{
			App::abort(403);
		}
		
		return Sentry::getGroupProvider()->createModel()->findOrFail($id);
	});
	Route::resource('groups', 'GroupsController');
	
	// User
	Route::bind('users', function($id, $route) {
		// Disallow edit, update and delete root user
		if ($route->getName() != 'users.show' and ($id == 1 or $id == Sentry::getUser()->id))
		{
			App::abort(403);
		}
		
		return Sentry::getUser()->findOrFail($id);
	});
	Route::resource('users', 'UsersController');
});

Route::group(array('before' => 'auth.sentry'), function()
{
    // Overview
	Route::resource('overview', 'OverviewController', array('only' => array('index')));
	
	// Account
	Route::bind('accounts', function($id, $route) {
		if (Sentry::getUser()->isSuperUser())
		{
			return Account::findOrFail($id);
		}
		else
		{
			return Sentry::getUser()->accounts()->findOrFail($id);
		}
	});
	Route::resource('accounts', 'AccountsController');

	// Log
	//Route::resource('logs', 'PanelLogsController', array('only' => array('index', 'show', 'destroy')));

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

