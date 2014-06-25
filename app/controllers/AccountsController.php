<?php

class AccountsController extends AuthorizedController {

	/**
	 * Initializer.
	 */
	public function __construct()
	{
		// Call parent
		parent::__construct();
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Grab all the domains for current user
		if (Sentry::getUser()->isSuperUser())
		{
			$accounts = Account::paginate();
		}
		else
		{
			$accounts = Sentry::getUser()->accounts()->paginate();
		}

		// Show the page
		return View::make('app.accounts.index', compact('accounts'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  model  $model
	 * @return Response
	 */
	public function show($account)
	{
		return View::make('app.accounts.show', compact('account'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if ( ! Sentry::getUser()->hasAccess('account.create'))
		{
			App::abort(403);
		}
		
		$allUsers = $selectedUsers = '';
		
		if (Sentry::getUser()->isSuperUser())
		{
			// Get all the available users except superusers
			$allUsers = array_filter(
							Sentry::getUserProvider()->findAll(),
							function($user)
							{
								return ! $user->hasAccess('superuser');
							}
						);
			
			// Selected users
			$selectedUsers = Input::old('users', array());
		}
		
		$indexPage = '';
		if (preg_match('#page=(\d+)#', URL::previous(), $matches))
		{
			$indexPage = $matches[1];
		}

		// Show the page
		return View::make('app.accounts.create', compact('allUsers', 'selectedUsers', 'indexPage'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if ( ! Sentry::getUser()->hasAccess('account.create'))
		{
			App::abort(403);
		}
		
		Input::merge(array(
			'home' => strtolower(trim(trim(str_replace('\\', '/', Input::get('home'))),	'/')),
			'ip'   => implode("\r\n", array_unique(array_filter(array_map('trim', explode("\r\n",str_replace(' ', '', Input::get('ip'))))))),
			'aliases' => implode("\r\n", array_unique(array_filter(array_map('trim', explode("\r\n",preg_replace('#(?:^|\r\n)([^/]*)/.*#', '$1', str_replace(array(' ', 'http://', 'https://'), '', strtolower(Input::get('aliases'))))))))),
		));

		$accountInstance = new Account;
		
		if ($accountInstance->validationFails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput(Input::except('password', 'password_confirmation'))->withErrors($accountInstance->getValidator());
		}
		
		// Saving Account
		if ( ! $accountInstance->store())
		{
			return Redirect::route('accounts.index')->with('error', Lang::get('accounts/messages.error.create'));
		}

		// Attaching Related Model for Aliases
		$accountInstance->storeAliases();
		
		// Attaching Related Model for IP
		$accountInstance->storeIp();

		// Attaching Related Model for User
		$accountInstance->storeUser();

		// Redirect to the user page
		return Redirect::route('accounts.index', array('page' => input::get('indexPage', 1)))->with('success',
			Lang::get('accounts/messages.success.create'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  model  $model
	 * @return Response
	 */
	public function edit($account)
	{
		if ( ! Sentry::getUser()->hasAccess('account.edit'))
		{
			App::abort(403);
		}
		
		$allUsers = $selectedUsers = '';
		
		if (Sentry::getUser()->isSuperUser())
		{
			// Get all the available users except superusers
			$allUsers = array_filter(
							Sentry::getUserProvider()->findAll(),
							function($user)
							{
								return ! $user->hasAccess('superuser');
							}
						);
			
			// Selected users
			$selectedUsers = Input::old('users', $account->users()->lists('id'));
		}
		
		$indexPage = '';
		if (preg_match('#page=(\d+)#', URL::previous(), $matches))
		{
			$indexPage = $matches[1];
		}
		
		$topDir = Libraries\Sadeghi85\UploadScript::getTopDir($account->home)['topDir'];
		$sharedHome = Account::where('home', 'LIKE', $topDir.'%')->lists('username');
		$sharedHome = array_diff($sharedHome, array($account->username));

		// Show the page
		return View::make('app.accounts.edit', compact('account', 'allUsers', 'selectedUsers', 'indexPage', 'sharedHome'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  model  $model
	 * @return Response
	 */
	public function update($account)
	{
		if ( ! Sentry::getUser()->hasAccess('account.edit'))
		{
			App::abort(403);
		}

		Input::merge(array(
			'home' => strtolower(trim(trim(str_replace('\\', '/', Input::get('home'))),	'/')),
			'ip'   => implode("\r\n", array_unique(array_filter(array_map('trim', explode("\r\n",str_replace(' ', '', Input::get('ip'))))))),
			'aliases' => implode("\r\n", array_unique(array_filter(array_map('trim', explode("\r\n",preg_replace('#(?:^|\r\n)([^/]*)/.*#', '$1', str_replace(array(' ', 'http://', 'https://'), '', strtolower(Input::get('aliases'))))))))),
		));

		$accountInstance = $account;
		
		$accountInstance->setValidationRules(array('username' => 'required|between:3,127|alpha_dash', 'password' => 'between:3,32|confirmed', 'password_confirmation' => 'between:3,32|same:password'));
		
		if ($accountInstance->validationFails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput(Input::except('password', 'password_confirmation'))->withErrors($accountInstance->getValidator());
		}
		
		// Saving Account
		if ( ! $accountInstance->store())
		{
			return Redirect::route('accounts.index')->with('error', Lang::get('accounts/messages.error.update'));
		}

		// Attaching Related Model for Aliases
		$accountInstance->storeAliases();
		
		// Attaching Related Model for IP
		$accountInstance->storeIp();

		// Attaching Related Model for User
		$accountInstance->storeUser();

		// Redirect to the user page
		return Redirect::route('accounts.index', array('page' => input::get('indexPage', 1)))->with('success',
			Lang::get('accounts/messages.success.update'));
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  model  $model
	 * @return Response
	 */
	public function destroy($account)
	{
		if ( ! Sentry::getUser()->hasAccess('account.delete'))
		{
			App::abort(403);
		}

		$account->delete();

		return Redirect::route('accounts.index', array('page' => Input::get('indexPage', 1)))->with('success',
			Lang::get('accounts/messages.success.delete'));
	}

}