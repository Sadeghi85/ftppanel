<?php

class GroupTableSeeder extends Seeder {

    public function run()
    {
        DB::table('groups')->delete();

        try
		{
		    // Create the group
		    Sentry::getGroupProvider()->create(array(
				'name'        => 'Root',
				'permissions' => array(
					'superuser' => 1,
				),
			));
		}
		catch (Cartalyst\Sentry\Groups\NameRequiredException $e)
		{
		    echo 'Name field is required';
		}
		catch (Cartalyst\Sentry\Groups\GroupExistsException $e)
		{
		    echo 'Group already exists';
		}
    }

}