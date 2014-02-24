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
		// Get all the available permissions
		$allPermissions = Config::get('permissions');
		$this->encodeAllPermissions($allPermissions, true);

		// Selected permissions
		$selectedPermissions = Input::old('permissions', array());

		// Get all the available groups
		$allGroups = Sentry::getGroupProvider()->findAll();

		// Selected groups
		$selectedGroups = Input::old('groups', array());
		
		$indexPage = '';
		if (preg_match('#page=(\d+)#', URL::previous(), $matches))
		{
			$indexPage = $matches[1];
		}

		// Show the page
		return View::make('app.users.create', compact('allPermissions', 'selectedPermissions', 'allGroups', 'selectedGroups', 'indexPage'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$userInstance = new User;
		
		if ($userInstance->validationFails(Input::all()))
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput(Input::except('password', 'password_confirmation'))->withErrors($userInstance->getValidator());
		}

		// We need to reverse the UI specific logic for our
		// permissions here before we create the user.
		$permissions = Input::get('permissions', array());
		$this->decodePermissions($permissions);
		
		if ( ! json_encode($permissions))
			return Redirect::back()->withInput()->with('error', 'Invalid form data.');
		
		try
		{
			// Get the inputs, with some exceptions
			$inputs = array_merge(Input::only('username', 'password', 'first_name', 'last_name', 'activated'), compact('permissions'));

			// Was the user created?
			if ($user = Sentry::getUserProvider()->create($inputs))
			{
				// Assign the selected groups to this user
				foreach (Input::get('groups', array()) as $groupId)
				{
					try
					{
						$group = Sentry::getGroupProvider()->findById($groupId);

						$user->addGroup($group);
					}
					catch (GroupNotFoundException $e)
					{

					}
				}

				// Log
				// $usernameToLog = $user->usernameWithFullName();
				// $currentUserUsername = Sentry::getUser()->usernameWithFullName();
				// $user->load('groups');
				// $myLog = new MyLog;
				// $myLog->insertLog(
					// array(
							// 'description' => sprintf('User [%s] has created the User [%s].%sCurrent Status:%s%s', $currentUserUsername, $usernameToLog, "\r\n\r\n", "\r\n\r\n", print_r($user->toArray(), true)),
							// 'user_id'     => Sentry::getUser()->id,
							// 'domain_id'   => null,
							// 'event'       => 'Create User',
							// 'type'        => 'info',
					// )
				// );

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
		try
		{
			// Get the user information
			$user = Sentry::getUserProvider()->findById($id);
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('users/messages.error.user_not_found', compact('id'));
			
			// Redirect to the groups management page
			return Redirect::route('users.index')->with('error', $error);
		}

		$allPermissions = Config::get('permissions');
		$selectedPermissions = $user->getMergedPermissions();
		
		$groups = $user->groups()->lists('name');
		
		return View::make('app.users.show', compact('user', 'groups', 'allPermissions', 'selectedPermissions'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// Disallow editing root
		if ($id == 1)
			App::abort('403');
		
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

		// Get all the available permissions
		$allPermissions = Config::get('permissions');
		$this->encodeAllPermissions($allPermissions, true);

		// Selected permissions
		$selectedPermissions = Input::old('permissions', array());

		// Get this user permissions
		$selectedPermissions = $user->getPermissions();
		$this->encodePermissions($selectedPermissions);
		
		$selectedPermissions = array_merge($selectedPermissions, Input::old('permissions', array()));
		
		// Get all the available groups
		$allGroups = Sentry::getGroupProvider()->findAll();

		// Get this user groups
		$selectedGroups = array_merge($user->getGroups()->lists('id'), Input::old('groups', array()));
		
		$indexPage = '';
		if (preg_match('#page=(\d+)#', URL::previous(), $matches))
		{
			$indexPage = $matches[1];
		}
		
		// Show the page
		return View::make('app.users.edit', compact('user', 'allGroups', 'selectedGroups', 'indexPage', 'allPermissions', 'selectedPermissions'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// Disallow editing root
		if ($id == 1)
			App::abort('403');
		
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

		//$usernameToLog = $user->usernameWithFullName();
		
		// Validation
		$user->setValidationRules(array(
			'username' => 'required|between:3,127|alpha_dash|unique:users,username,'.$id,
			'password'         => 'between:3,32|confirmed',
			'password_confirmation'  => 'between:3,32|same:password',
		));
		
		if ($user->validationFails(Input::all()))
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput(Input::except('password', 'password_confirmation'))->withErrors($user->getValidator());
		}
		
		// We need to reverse the UI specific logic for our
		// permissions here before we update the group.
		$permissions = Input::get('permissions', array());
		$this->decodePermissions($permissions);

		if ( ! json_encode($permissions))
			return Redirect::back()->withInput()->with('error', 'Invalid form data.');
		
		try
		{
			// Update the user
			$inputs = array_filter(array_merge(Input::only('username', 'password', 'first_name', 'last_name', 'activated'), compact('permissions')));
			// Log the user out
			$inputs['persist_code'] = null;
			
			// Was the user updated?
			if ($user->update($inputs))
			{
				// Get the current user groups
				$groups = $user->getGroups()->lists('id');

				// Get the selected groups
				$selectedGroups = Input::get('groups', array());

				// Groups comparison between the groups the user currently
				// have and the groups the user wish to have.
				$groupsToAdd    = array_diff($selectedGroups, $groups);
				$groupsToRemove = array_diff($groups, $selectedGroups);

				// Assign the user to groups
				foreach ($groupsToAdd as $groupId)
				{
					try
					{
						$group = Sentry::getGroupProvider()->findById($groupId);

						$user->addGroup($group);
					}
					catch (GroupNotFoundException $e)
					{

					}
				}

				// Remove the user from groups
				foreach ($groupsToRemove as $groupId)
				{
					try
					{
						$group = Sentry::getGroupProvider()->findById($groupId);

						$user->removeGroup($group);
					}
					catch (GroupNotFoundException $e)
					{

					}
				}
				
				// Log
				// $currentUserUsername = Sentry::getUser()->usernameWithFullName();
				// $user->load('groups');
				// $myLog = new MyLog;
				// $myLog->insertLog(
					// array(
							// 'description' => sprintf('User [%s] has edited the User [%s].%sCurrent Status:%s%s', $currentUserUsername, $usernameToLog, "\r\n\r\n", "\r\n\r\n", print_r($user->toArray(), true)),
							// 'user_id'     => Sentry::getUser()->id,
							// 'domain_id'   => null,
							// 'event'       => 'Edit User',
							// 'type'        => 'info',
					// )
				// );
				
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
		// Disallow deleting root
		if ($id == 1)
			App::abort('403');
		
		try
		{
			// Get user information
			$user = Sentry::getUserProvider()->findById($id);

			// Check if we are not trying to delete ourself
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
			// $usernameToLog = $user->usernameWithFullName();
			// $currentUserUsername = Sentry::getUser()->usernameWithFullName();
			// $myLog = new MyLog;
			// $myLog->insertLog(
				// array(
						// 'description' => sprintf('User [%s] has deleted the User [%s].', $currentUserUsername, $usernameToLog),
						// 'user_id'     => Sentry::getUser()->id,
						// 'domain_id'   => null,
						// 'event'       => 'Delete User',
						// 'type'        => 'warning',
				// )
			// );
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('users/messages.user_not_found', compact('id' ));

			// Redirect to the user management page
			return Redirect::back()->with('error', $error);
		}
		
		// Prepare the success message
		$success = Lang::get('users/messages.success.delete');

		// Redirect to the user management page
		return Redirect::back()->with('success', $success);
	}

}