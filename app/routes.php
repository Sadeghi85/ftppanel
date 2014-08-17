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

Route::post('upload', function()
{
	$response = array(
		'success' => 0,
		'video'   => '',
		'thumb'   => '',
		'preview' => ''
	);
	
	$hasVideo   = (Input::hasFile('cdn_video')   and Input::file('cdn_video')->isValid())   ? true : false;
	$hasThumb   = (Input::hasFile('cdn_thumb')   and Input::file('cdn_thumb')->isValid())   ? true : false;
	$hasPreview = (Input::hasFile('cdn_preview') and Input::file('cdn_preview')->isValid()) ? true : false;
	
	if ($hasVideo) {
		$localVideo = Input::file('cdn_video')->getRealPath();
		$remoteVideo = Input::file('cdn_video')->getClientOriginalName();
	} else {
		return json_encode($response);
	}
	if ($hasThumb) {
		$localThumb = Input::file('cdn_thumb')->getRealPath();
		$remoteThumb = Input::file('cdn_thumb')->getClientOriginalName();
	}
	if ($hasPreview) {
		$localPreview = Input::file('cdn_preview')->getRealPath();
		$remotePreview = Input::file('cdn_preview')->getClientOriginalName();
	}
	
	$username = Input::get('cdn_username', '');
	$password = Input::get('cdn_password', '');
	
	$account = Account::where('username', $username)->where('password', sha1($password));
	if ($account) {
		// order of these two lines is important
		$domain = $account->first()->aliases[0]->domain;
		$home = str_replace(Config::get('ftppanel.ftpHome'), '', $account->pluck('home'));
	} else {
		return json_encode($response);
	}
	
	if (Input::has('categoryslugs') and Input::has('category')) {
		$categorySlugs = json_decode(base64_decode(Input::get('categoryslugs')), true);
		$dir = $categorySlugs[Input::get('category')];
	} else {
		$dir = Input::get('category', '');
	}
	
	if ($resFtp = @ftp_connect('localhost')) {
		if (@ftp_login($resFtp, $username, $password)) {
			if (@ftp_pasv($resFtp, true)) {
				@ftp_raw($resFtp, 'OPTS UTF-8 ON');
				@ftp_mkdir($resFtp, $dir);
				if (@ftp_chdir($resFtp, $dir)) {
					if ($hasVideo and @ftp_put($resFtp, $remoteVideo, $localVideo, FTP_BINARY)) {
						$response['success']   = 1;
						$response['video']     = sprintf('http://%s/%s/%s/%s', $domain, $home, $dir, $remoteVideo);
					} else {
						return json_encode($response);
					}
					if ($hasThumb and @ftp_put($resFtp, $remoteThumb, $localThumb, FTP_BINARY)) {
						$response['thumb']     = sprintf('http://%s/%s/%s/%s', $domain, $home, $dir, $remoteThumb);
					}
					if ($hasPreview and @ftp_put($resFtp, $remotePreview, $localPreview, FTP_BINARY)) {
						$response['preview']   = sprintf('http://%s/%s/%s/%s', $domain, $home, $dir, $remotePreview);
					}
				}
			}
		}
	}
	
	return json_encode($response);
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
		$file = rtrim(urldecode($file), '/');
		
		$topDir = Libraries\Sadeghi85\UploadScript::getTopDir($file)['topDir'];
		$relativeFile = Libraries\Sadeghi85\UploadScript::getTopDir($file)['relativeFile'];

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
			$txtContent .= sprintf('http://%s/%s%s', $alias, $relativeFile, "\r\n");
		}
		
		shell_exec(sprintf('echo "%s" | sudo tee "%s"', $txtContent, $file.'.txt'));
		
		$sharedHome = $accountsWithSameTopLevelDir->where('readonly', '=', 1)->lists('username');
		
		if ( ! empty($sharedHome))
		{
			Event::fire('account.readonly_upload', array($topDir));
		}
	}
	
	return;
});

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

