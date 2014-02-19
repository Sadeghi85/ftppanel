<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();
		
        try
		{
		    // Create the user
		    $user = Sentry::createUser(array(
		        'username'    => 'root',
		        'password' => 'root',
		    ));

		    // Find the group using the group name
		    $rootGroup = Sentry::findGroupByName('Root');

		    // Assign the group to the user
		    $user->addGroup($rootGroup);
		}
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		    echo 'Login field is required.';
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
		    echo 'Password field is required.';
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    echo 'User with this login already exists.';
		}
		catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
		    echo 'Group was not found.';
		}

		try
		{
		    // Attempt to activate the user
		    $user->attemptActivation('');
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    echo 'User was not found.';
		}
		catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
		{
		    echo 'User is already activated.';
		}
    }
}