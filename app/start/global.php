<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::fatal(function($exception)
{
	Log::error($exception);
	
	if ( ! Config::get('app.debug'))
	{
		return Response::make(View::make('error/500'), 500);
	}
});

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
	
	if ( ! Config::get('app.debug'))
	{
		switch ($code)
		{
			case 403:
				return Response::make(View::make('error/403'), 403);

			case 500:
				return Response::make(View::make('error/500'), 500);
				
			case 503:
				return Response::make(View::make('error/503'), 503);

			default:
				return Response::make(View::make('error/404'), 404);
		}
	}
});

App::error(function(Illuminate\Session\TokenMismatchException $exception, $code)
{
    return Response::make(View::make('error/token_mismatch'), 403);
});

App::error(function(Illuminate\Database\Eloquent\ModelNotFoundException $exception, $code)
{
    return Response::make(View::make('error/404'), 404);
});

App::missing(function($exception)
{
    return Response::make(View::make('error/404'), 404);
});



/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	//return Response::make("Be right back!", 503);
	
	return Response::make(View::make('error/503'), 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/*
|--------------------------------------------------------------------------
| Validator Extends
|--------------------------------------------------------------------------
|
*/

Validator::extend('custom.ip_range', function($attribute, $value, $parameters)
{
	$inputs = explode("\r\n", trim($value));

	foreach ($inputs as $input)
	{
		$ip = explode('-', $input);
		
		if (count($ip) > 2)
			return false;

		{
			$validator = Validator::make(array($attribute => $ip[0]), array($attribute => 'ip'));

			if ($validator->fails())
				return false;
		}
		
		if (count($ip) == 2)
		{
			$validator = Validator::make(array($attribute => $ip[1]), array($attribute => 'ip'));

			if ($validator->fails())
				return false;
		}
	}

	return true;
});

Validator::extend('custom.domain', function($attribute, $value, $parameters)
{
	$inputs = explode("\r\n", trim($value));

	foreach ($inputs as $input)
	{
		if (preg_match('#^\d+(?:\.\d+)*$#', $input))
		{
			return false;
		}

		if ( ! preg_match('#^(?=.{1,255}$)[0-9A-Za-z](?:(?:[0-9A-Za-z]|\b-){0,61}[0-9A-Za-z])?(?:\.[0-9A-Za-z](?:(?:[0-9A-Za-z]|\b-){0,61}[0-9A-Za-z])?)*$#', $input))
		{
			return false;
		}
	}

	return true;
});

/*
|--------------------------------------------------------------------------
| Blade Extends
|--------------------------------------------------------------------------
|
*/

Blade::extend(function($value)
{
	return preg_replace('/@php((.|\s)*?)@endphp/', '<?php $1 ?>', $value);
});

Blade::extend(function($value)
{
	return preg_replace_callback('/@comment((.|\s)*?)@endcomment/',
              function ($matches) {
                    return '<?php /* ' . preg_replace('/@|\{/', '\\\\$0\\\\', $matches[1]) . ' */ ?>';
              },
              $value
			);
});

/*
|--------------------------
| Events
|--------------------------
*/

Event::listen('account.readonly_upload', function($dir)
{
    Libraries\Sadeghi85\UploadScript::setReadonly($dir);
}, 1);

Event::listen('account.normal_upload', function($dir)
{
    Libraries\Sadeghi85\UploadScript::unsetReadonly($dir);
}, 1);

Event::listen('account.create_home', function($dir)
{
    Libraries\Sadeghi85\UploadScript::createHome($dir);
}, 2);

/*
|--------------------------
| Helpers
|--------------------------
*/

function encodeURI($url)
{
    // http://php.net/manual/en/function.rawurlencode.php
    // https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/encodeURI
    $unescaped = array(
        '%2D'=>'-','%5F'=>'_','%2E'=>'.','%21'=>'!', '%7E'=>'~',
        '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')'
    );
    $reserved = array(
        '%3B'=>';','%2C'=>',','%2F'=>'/','%3F'=>'?','%3A'=>':',
        '%40'=>'@','%26'=>'&','%3D'=>'=','%2B'=>'+','%24'=>'$'
    );
    $score = array(
        '%23'=>'#'
    );
    return strtr(rawurlencode($url), array_merge($reserved,$unescaped,$score));

}

/*
|--------------------------
| View Composers
|--------------------------
*/

View::composer(Paginator::getViewName(), function($view) {
	$queryString = array_except(Input::query(), Paginator::getPageName());
	$view->paginator->appends($queryString);
});

/*
|--------------------------------------------------------------------------
| Global Constant
|--------------------------------------------------------------------------
|
*/

// To check if Views are run from inside the framework
define('VIEW_IS_ALLOWED', true);


header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Referer, User-Agent');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Origin: *');


