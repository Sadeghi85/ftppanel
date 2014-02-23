<?php

return array(

	// Area -> Permissions -> Permission
	
	'Administration' => array(
		array(
			'permission' => 'superuser',
			'label'      => 'Super User',
		),
	),
	
	'Account' => array(
		array(
			'permission' => 'account.create',
			'label'      => 'Create',
		),
		
		array(
			'permission' => 'account.edit',
			'label'      => 'Edit',
		),
		
		array(
			'permission' => 'account.delete',
			'label'      => 'Delete',
		),
	),

	'Log' => array(
		array(
			'permission' => 'log.own',
			'label'      => 'View own logs',
		),
		
		array(
			'permission' => 'log.users',
			'label'      => 'View all logs except super users',
		),
		
		array(
			'permission' => 'log.superusers',
			'label'      => 'View all logs',
		),
	),
);
