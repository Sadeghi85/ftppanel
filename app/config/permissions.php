<?php

return array(

	'Site' => array(
		array(
			'permission' => 'site.create',
			'label'      => 'Create',
			'allow'      => 0,
		),
		
		array(
			'permission' => 'site.edit',
			'label'      => 'Edit',
			'allow'      => 0,
		),
		
		array(
			'permission' => 'site.delete',
			'label'      => 'Delete',
			'allow'      => 0,
		),
	),
	
	'Log' => array(
		array(
			'permission' => 'log.self',
			'label'      => 'Only Self',
			'allow'      => 1,
		),
		
		array(
			'permission' => 'log.nonroot',
			'label'      => 'All Non-Root',
			'allow'      => 0,
		),
		
		array(
			'permission' => 'log.all',
			'label'      => 'All including Root',
			'allow'      => 0,
		),
	),


);
