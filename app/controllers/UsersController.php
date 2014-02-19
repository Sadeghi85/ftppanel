<?php

use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserExistsException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Groups\GroupNotFoundException;

class UsersController extends RootController {

	/**
	 * Initializer.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Call parent
		parent::__construct();
	}
	
	/**
	 * Declare the rules for the form validation
	 *
	 * @var array
	 */
	protected $validationRules = array(
		'username'       => 'required|between:3,127|alpha_dash|unique:users,username',
		'first_name'       => 'between:3,127|alpha_dash',
		'last_name'       => 'between:3,127|alpha_dash',
		'password'         => 'required|between:3,32|confirmed',
	);
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Grab all the users
		$users = Sentry::getUserProvider()->createModel()->paginate();

		// Show the page
		return View::make('app.users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// Get all the available groups
		$groups = Sentry::getGroupProvider()->findAll();

		// Selected group
		$selectedGroup = Input::old('group', '-1');
		
		$indexPage = '';
		if (preg_match('#page=(\d+)#', URL::previous(), $matches))
		{
			$indexPage = $matches[1];
		}

		// Show the page
		return View::make('app.users.create', compact('groups', 'selectedGroup', 'indexPage'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// Create a new validator instance from our validation rules
		$validator = Validator::make(Input::all(), $this->validationRules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput(Input::except('password', 'password_confirmation'))->withErrors($validator);
		}

		try
		{
			// Find the Group
			$groupId = Input::get('group', '-1');
			$group = Sentry::getGroupProvider()->findById($groupId);
			$assignGroup = true;
		}
		catch (GroupNotFoundException $e)
		{
			$assignGroup = false;
		}
		
		try
		{
			// Get the inputs, with some exceptions
			$inputs = Input::except('csrf_token', 'password_confirmation', 'group', 'indexPage');

			// Was the user created?
			if ($user = Sentry::getUserProvider()->create($inputs))
			{
				// Assign the selected group to this user
				if ($assignGroup) { $user->addGroup($group); }
				
				// Log
				$usernameToLog = $user->usernameWithFullName();
				$currentUserUsername = Sentry::getUser()->usernameWithFullName();
				$user->load('groups');
				$myLog = new MyLog;
				$myLog->insertLog(
					array(
							'description' => sprintf('User [%s] has created the User [%s].%sCurrent Status:%s%s', $currentUserUsername, $usernameToLog, "\r\n\r\n", "\r\n\r\n", print_r($user->toArray(), true)),
							'user_id'     => Sentry::getUser()->id,
							'domain_id'   => null,
							'event'       => 'Create User',
							'type'        => 'info',
					)
				);

				// Prepare the success message
				$success = Lang::get('users/messages.success.create');

				// Redirect to the users management page
				return Redirect::route('users.index', array('page' => input::get('indexPage', 1)))->with('success', $success);
			}

			// Prepare the error message
			$error = Lang::get('users/messages.error.create');

			// Redirect to the user creation page
			return Redirect::route('users.index')->with('error', $error);
		}
		catch (LoginRequiredException $e)
		{
			$error = 'user_login_required';
		}
		catch (PasswordRequiredException $e)
		{
			$error = 'user_password_required';
		}
		catch (UserExistsException $e)
		{
			$error = 'user_exists';
		}
		
		// Redirect to the user creation page
		return Redirect::back()->withInput(Input::except('password', 'password_confirmation'))->with('error', Lang::get('users/messages.error.'.$error));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		try
		{
			// Get the user information
			$user = Sentry::getUserProvider()->findById($id);

			// Get this user group
			$userGroup = $user->groups()->pluck('id');

			// Get a list of all the available groups
			$groups = Sentry::getGroupProvider()->findAll();
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('users/messages.error.user_not_found', compact('id'));

			// Redirect to the user management page
			return Redirect::route('users.index')->with('error', $error);
		}

		$indexPage = '';
		if (preg_match('#page=(\d+)#', URL::previous(), $matches))
		{
			$indexPage = $matches[1];
		}
		
		// Show the page
		return View::make('app.users.edit', compact('user', 'groups', 'userGroup', 'indexPage'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		try
		{
			// Get the user information
			$user = Sentry::getUserProvider()->findById($id);
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('users/messages.error.user_not_found', compact('id'));

			// Redirect to the user management page
			return Redirect::route('users.index')->with('error', $error);
		}

		$usernameToLog = $user->usernameWithFullName();
		
		$this->validationRules['username'] = 'required|between:3,127|alpha_dash|unique:users,username,'.$user->id;

		// Do we want to update the user password?
		if ( ! $password = Input::get('password'))
		{
			unset($this->validationRules['password']);
			unset($this->validationRules['password_confirmation']);
		}

		// Create a new validator instance from our validation rules
		$validator = Validator::make(Input::all(), $this->validationRules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput(Input::except('password', 'password_confirmation'))->withErrors($validator);
		}

		try
		{
			// Find the Group
			$groupId = Input::get('group', '-1');
			$group = Sentry::getGroupProvider()->findById($groupId);
			$assignGroup = true;
		}
		catch (GroupNotFoundException $e)
		{
			$assignGroup = false;
		}
		
		try
		{
			// Get this user group
			$userGroupId = $user->groups()->pluck('id');
			$userGroup = Sentry::getGroupProvider()->findById($userGroupId);
			$hasGroup = true;
		}
		catch (GroupNotFoundException $e)
		{
			$hasGroup = false;
		}
		
		try
		{
			// Update the user
			$user->first_name  = Input::get('first_name');
			$user->last_name   = Input::get('last_name');
			$user->username    = Input::get('username');
			$user->activated   = Input::get('activated', $user->activated);

			// Do we want to update the user password?
			if ($password)
			{
				$user->password = $password;
			}

			// Update user group
			if ($userGroupId != $groupId)
			{
				if ($hasGroup)
				{
					$user->removeGroup($userGroup);
				}

				if ($assignGroup)
				{
					$user->addGroup($group);
				}
			}
			
			// Log the user out
			$user->persist_code = null;
			
			// Was the user updated?
			if ($user->save())
			{
				// Log
				$currentUserUsername = Sentry::getUser()->usernameWithFullName();
				$user->load('groups');
				$myLog = new MyLog;
				$myLog->insertLog(
					array(
							'description' => sprintf('User [%s] has edited the User [%s].%sCurrent Status:%s%s', $currentUserUsername, $usernameToLog, "\r\n\r\n", "\r\n\r\n", print_r($user->toArray(), true)),
							'user_id'     => Sentry::getUser()->id,
							'domain_id'   => null,
							'event'       => 'Edit User',
							'type'        => 'info',
					)
				);
				
				// Prepare the success message
				$success = Lang::get('users/messages.success.update');

				// Redirect to the user management page
				return Redirect::route('users.index', array('page' => input::get('indexPage', 1)))->with('success', $success);
				
			}

			// Prepare the error message
			$error = Lang::get('users/messages.error.update');
		}
		catch (LoginRequiredException $e)
		{
			$error = Lang::get('users/messages.error.user_login_required');
		}

		// Redirect to the user page
		return Redirect::back()->withInput(Input::except('password', 'password_confirmation'))->with('error', $error);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try
		{
			// Get user information
			$user = Sentry::getUserProvider()->findById($id);

			// Check if we are not trying to delete ourselves
			if ($user->id === Sentry::getId())
			{
				// Prepare the error message
				$error = Lang::get('users/messages.error.delete');

				// Redirect to the user management page
				return Redirect::back()->with('error', $error);
			}

			// Delete the user
			$user->delete();

			// Log
			$usernameToLog = $user->usernameWithFullName();
			$currentUserUsername = Sentry::getUser()->usernameWithFullName();
			$myLog = new MyLog;
			$myLog->insertLog(
				array(
						'description' => sprintf('User [%s] has deleted the User [%s].', $currentUserUsername, $usernameToLog),
						'user_id'     => Sentry::getUser()->id,
						'domain_id'   => null,
						'event'       => 'Delete User',
						'type'        => 'warning',
				)
			);
			
			// Prepare the success message
			$success = Lang::get('users/messages.success.delete');

			// Redirect to the user management page
			return Redirect::back()->with('success', $success);
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('users/messages.user_not_found', compact('id' ));

			// Redirect to the user management page
			return Redirect::back()->with('error', $error);
		}
	}

}