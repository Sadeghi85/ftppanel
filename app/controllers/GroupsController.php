<?php

use Cartalyst\Sentry\Groups\GroupExistsException;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Groups\NameRequiredException;

class GroupsController extends RootController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Grab all the groups
		$groups = Sentry::getGroupProvider()->createModel()->paginate();

		// Show the page
		return View::make('app.groups.index', compact('groups'));
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

		// Show the page
		return View::make('app.groups.create', compact('allPermissions', 'selectedPermissions'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$groupInstance = new Group;
		
		if ($groupInstance->validationFails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput()->withErrors($groupInstance->getValidator());
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
			$inputs = array_merge(Input::only('name'), compact('permissions'));

			// Was the group created?
			if ($group = Sentry::getGroupProvider()->create($inputs))
			{				
				// Log
				// $groupNameToLog = $group->name;
				// $currentUserUsername = Sentry::getUser()->usernameWithFullName();
				// $myLog = new MyLog;
				// $myLog->insertLog(
					// array(
							// 'description' => sprintf('User [%s] has edited the Group [%s].%sCurrent Status:%s%s', $currentUserUsername, $groupNameToLog, "\r\n\r\n", "\r\n\r\n", print_r($group->toArray(), true)),
							// 'user_id'     => Sentry::getUser()->id,
							// 'domain_id'   => null,
							// 'event'       => 'Create Group',
							// 'type'        => 'info',
					// )
				// );
				
				// Redirect to the group management page
				return Redirect::route('groups.index')->with('success', Lang::get('groups/messages.success.create'));
			}

			// Redirect to the new group page
			return Redirect::back()->with('error', Lang::get('groups/messages.error.create'));
		}
		catch (NameRequiredException $e)
		{
			$error = 'group_name_required';
		}
		catch (GroupExistsException $e)
		{
			$error = 'group_exists';
		}

		// Redirect to the group create page
		return Redirect::back()->withInput()->with('error', Lang::get('groups/messages.error.'.$error));
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
			// Get the group information
			$group = Sentry::getGroupProvider()->findById($id);
		}
		catch (GroupNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('groups/messages.error.group_not_found', compact('id'));
			
			// Redirect to the groups management page
			return Redirect::route('groups.index')->with('error', $error);
		}

		$allPermissions = Config::get('permissions');
		$selectedPermissions = $group->permissions;
		
		return View::make('app.groups.show', compact('group', 'allPermissions', 'selectedPermissions'));
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
			// Get the group information
			$group = Sentry::getGroupProvider()->findById($id);
		}
		catch (GroupNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('groups/messages.error.group_not_found', compact('id'));
			
			// Redirect to the groups management page
			return Redirect::route('groups.index')->with('error', $error);
		}
		
		// Get all the available permissions
		$allPermissions = Config::get('permissions');
		$this->encodeAllPermissions($allPermissions, true);

		// Get this group permissions
		$selectedPermissions = $group->getPermissions();
		$this->encodePermissions($selectedPermissions);
		
		$selectedPermissions = array_replace($selectedPermissions, Input::old('permissions', array()));

		// Show the page
		return View::make('app.groups.edit', compact('group', 'allPermissions', 'selectedPermissions'));
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
			// Get the group information
			$group = Sentry::getGroupProvider()->findById($id);
		}
		catch (GroupNotFoundException $e)
		{
			// Redirect to the groups management page
			return Rediret::route('groups.index')->with('error', Lang::get('groups/messages.error.group_not_found', compact('id')));
		}

		// Validation
		$group->setValidationRules(array(
			'name' => 'required|between:3,127|alpha_dash|unique:groups,name,'.$id,
		));
		
		if ($group->validationFails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput()->withErrors($group->getValidator());
		}
		
		// We need to reverse the UI specific logic for our
		// permissions here before we update the group.
		$permissions = Input::get('permissions', array());
		$this->decodePermissions($permissions);

		if ( ! json_encode($permissions))
				return Redirect::back()->withInput()->with('error', 'Invalid form data.');
		
		try
		{
			// Update the group data
			$inputs = array_merge(Input::only('name'), compact('permissions'));
			
			// Was the group updated?
			if ($group->update($inputs))
			{
				// Log all this group's users out
				$users = Sentry::findAllUsersInGroup($group);
				
				foreach ($users as $user)
				{
					$user->persist_code = null;
					$user->save();
				}
				
				// Log
				// $currentUserUsername = Sentry::getUser()->usernameWithFullName();
				// $myLog = new MyLog;
				// $myLog->insertLog(
					// array(
							// 'description' => sprintf('User [%s] has edited the Group [%s].%sCurrent Status:%s%s', $currentUserUsername, $groupNameToLog, "\r\n\r\n", "\r\n\r\n", print_r($group->toArray(), true)),
							// 'user_id'     => Sentry::getUser()->id,
							// 'domain_id'   => null,
							// 'event'       => 'Edit Group',
							// 'type'        => 'info',
					// )
				// );
			
				// Redirect to the group page
				return Redirect::route('groups.index')->with('success', Lang::get('groups/messages.success.update'));
			}
			else
			{
				// Redirect to the group page
				return Redirect::back()->with('error', Lang::get('groups/messages.error.update'));
			}
		}
		catch (NameRequiredException $e)
		{
			$error = Lang::get('groups/messages.error.group_name_required');
		}

		// Redirect to the group page
		return Redirect::back()->withInput()->with('error', $error);
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
			// Get group information
			$group = Sentry::getGroupProvider()->findById($id);
			
			// Log all this group's users out
			$users = Sentry::findAllUsersInGroup($group);
			
			foreach ($users as $user)
			{
				$user->persist_code = null;
				$user->save();
			}
			
			// Delete the group
			$group->delete();
		}
		catch (GroupNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('groups/messages.error.group_not_found', compact('id'));
			
			// Redirect to the group management page
			return Redirect::back()->with('error', $error);
		}
		
		
		
		// Log
		// $currentUserUsername = Sentry::getUser()->usernameWithFullName();
		// $myLog = new MyLog;
		// $myLog->insertLog(
			// array(
					// 'description' => sprintf('User [%s] has deleted the Group [%s].', $currentUserUsername, $group->name),
					// 'user_id'     => Sentry::getUser()->id,
					// 'domain_id'   => null,
					// 'event'       => 'Delete Group',
					// 'type'        => 'warning',
			// )
		// );

		// Redirect to the group management page
		return Redirect::back()->with('success', Lang::get('groups/messages.success.delete'));
	}

}