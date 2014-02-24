<?php

return array(

	'error' => array(
		'create' => 'User couldn\'t be created.',
		'update' => 'User couldn\'t be updated.',
		'delete' => 'User couldn\'t be deleted.',
		
		'user_exists'        => 'User already exists!',
		'user_not_found'           => 'User [:id] doesn\'t exist.',
		'user_login_required'      => 'The login field is required',
		'user_password_required'   => 'The password field is required.',
		
	),
	
	'success' => array(
		'create' => 'User is successfully created.',
		'update' => 'User is successfully updated.',
		'delete' => 'User is successfully deleted.',
	),
	

	'index' => array(
		'title' => 'Users',
		
		'header' => 'User Management',
		
		'create' => 'Create',
		
		'id' => 'Id',
		'name' => 'Name',
		'username' => 'Username',
		'activated' => 'Activated',
		'created_at' => 'Created at',
		'actions' => 'Actions',
	
	),
	
	'show' => array(
		'title' => 'Users',

		'header' => 'User [:id]',

		'back' => 'Back',

		'key' => 'Key',
		'value' => 'Value',

		'id' => 'Id',
		'username' => 'Username',
		'first_name' => 'First Name',
		'last_name' => 'Last Name',
		'activated' => 'Activated',
		'groups' => 'Groups',
		
		'permissions' => 'Permissions',
		'permission' => 'Permission',
		'resource' => 'Resource',

		'created_at' => 'Created At',
		'updated_at' => 'Updated At',
	),
	
	'create' => array(
		'title' => 'Create a User',
		'header' => 'Create a New User',
		
		'back' => 'Back',
		
		'username' => 'Username',
		'password' => 'Password',
		'password_confirmation' => 'Confirm Password',
		'first_name' => 'First Name',
		'last_name' => 'Last Name',
		'activated' => 'Activate',
		'groups' => 'Groups',
		
	),
	
	'edit' => array(
		'title' => 'Edit User',
		'header' => 'Edit User',
		
		'back' => 'Back',
		
		'username' => 'Username',
		'password' => 'Password',
		'password_confirmation' => 'Confirm Password',
		'first_name' => 'First Name',
		'last_name' => 'Last Name',
		'activated' => 'Activate',
		'groups' => 'Groups',
		
	),
);
