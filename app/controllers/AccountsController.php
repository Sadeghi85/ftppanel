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
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if (Sentry::getUser()->isSuperUser())
		{
			$account = Account::with('ip')->find($id);
		}
		else
		{
			$account = Sentry::getUser()->accounts()->with('ip')->find($id);
		}

		if ( ! $account)
		{
			return Redirect::route('accounts.index')->with('error', Lang::get('accounts/messages.error.account_not_found',
				compact('id')));
		}

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
		));

		// Create a new validator instance from our validation rules
		$validator = Validator::make(Input::all(), $this->validationRules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput(Input::except('password', 'password_confirmation'))->withErrors($validator);
		}

		$users = Input::get('users', array());
		$username = Input::get('username');
		$password = Input::get('password');
		$home = Input::get('home');
		$ipCollection = array_filter(explode("\r\n", Input::get('ip')));
		$ulbandwidth = (int) Input::get('ulbandwidth');
		$dlbandwidth = (int) Input::get('dlbandwidth');
		$quotasize = (int) Input::get('quotasize');
		$quotafiles = (int) Input::get('quotafiles');
		$comment = Input::get('comment');
		$activated = (int) Input::get('activated', 0);

		// Saving Account
		try
		{
			$account = new Account(array(
				'username' => $username,
				'home' => Config::get('ftppanel.ftpHome').'/'.$home,
				'ulbandwidth' => $ulbandwidth,
				'dlbandwidth' => $dlbandwidth,
				'quotasize' => $quotasize,
				'quotafiles' => $quotafiles,
				'comment' => e($comment),
				'activated' => $activated,
			));

			$account->password = sha1($password);

			$account->save();
		}
		catch (\Exception $e)
		{
			return Redirect::route('accounts.index')->with('error', Lang::get('accounts/messages.error.create'));
		}

		// Attaching Related Model for IP
		$account->insertIp($ipCollection);

		// Attaching Related Model for User
		$account->insertUser($users);

		// Redirect to the user page
		return Redirect::route('accounts.index', array('page' => input::get('indexPage', 1)))->with('success',
			Lang::get('accounts/messages.success.create'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if ( ! (Group::isRoot() or Sentry::getUser()->hasAccess('account.edit')))
		{
			App::abort(403);
		}

		$users = $selectedUsers = '';

		if (Group::isRoot())
		{
			// Get all the available users
			$users = Sentry::getUserProvider()->findAll();

			// Selected users
			$selectedUsers = Input::old('users', array());

			if (empty($selectedUsers))
			{
				$account = Account::find($id);

				if ( ! $account)
				{
					return Redirect::route('accounts.index')->with('error', Lang::get('accounts/messages.error.account_not_found', compact('id')));
				}

				$selectedUsers = $account->users()->lists('id');
			}
		}

		$indexPage = '';
		if (preg_match('#page=(\d+)#', URL::previous(), $matches))
		{
			$indexPage = $matches[1];
		}

		// Show the page
		return View::make('app.accounts.edit', compact('users', 'selectedUsers', 'indexPage'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if ( ! (Group::isRoot() or Sentry::getUser()->hasAccess('account.delete')))
		{
			App::abort(403);
		}

		$account = Account::find($id);

		if ( ! $account)
		{
			return Redirect::route('accounts.index')->with('error', Lang::get('accounts/messages.error.account_not_found',
				compact('id')));
		}

		$account->delete();

		return Redirect::route('accounts.index', array('page' => Input::get('indexPage', 1)))->with('success',
			Lang::get('accounts/messages.success.delete'));
	}

}