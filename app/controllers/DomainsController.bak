<?php

class DomainsController extends AuthorizedController {

	/**
	 * Initializer.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Call parent
		parent::__construct();
		
		Validator::extend('custom.domain', function($attribute, $value, $parameters)
		{
			$inputs = explode("\r\n", trim($value));
			
			foreach ($inputs as $input)
			{
				if (preg_match('#^\d+(?:\.\d+)+$#', $input))
				{
					return false;
				}
				
				if ( ! preg_match('#^(?=.{3,255}$)[0-9A-Za-z](?:(?:[0-9A-Za-z]|\b-){0,61}[0-9A-Za-z])?(?:\.[0-9A-Za-z](?:(?:[0-9A-Za-z]|\b-){0,61}[0-9A-Za-z])?)+$#', $input))
				{
					return false;
				}
			}
			
			
			return true;
		});
		
		Validator::extend('custom.exists_array', function($attribute, $value, $parameters)
		{
			if (count($parameters) != 2) { return false; }
			
			if ( ! is_array($value))
			{
				$inputs = array($value);
			}
			else
			{
				$inputs = $value;
			}
			
			foreach ($inputs as $input)
			{
				$validator = Validator::make(array($attribute => $input), array($attribute => sprintf('exists:%s,%s', $parameters[0], $parameters[1])));

				if ($validator->fails()) { return false; }
			}
			
			return true;
		});
	}
	
	/**
	 * Declare the rules for the form validation
	 *
	 * @var array
	 */
	protected $validationRules = array(
		// TODO: add ip_port to db, unique index on (name,ip_port), custom validation for composite unique index
		//'name'       => 'required|custom.domain|unique:domains,name',
		'name'       => 'required|custom.domain',
		'alias'       => 'custom.domain',
		//'users'       => 'custom.exists_array:users,id',
	);
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Grab all the domains for current user
		if (Group::isRoot())
		{
			$domains = Domain::paginate();
		}
		else
		{
			$domains = Sentry::getUser()->domains()->paginate();
		}

		// Show the page
		return View::make('app.domains.index', compact('domains'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if ( ! (Group::isRoot() or Sentry::getUser()->hasAccess('domain.create')))
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
		}
		
		$indexPage = '';
		if (preg_match('#page=(\d+)#', URL::previous(), $matches))
		{
			$indexPage = $matches[1];
		}

		// Show the page
		return View::make('app.domains.create', compact('users', 'selectedUsers', 'indexPage'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if (Group::isRoot())
		{
			$this->validationRules['users'] = 'custom.exists_array:users,id';
		}
	
		// Create a new validator instance from our validation rules
		$validator = Validator::make(Input::all(), $this->validationRules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput()->withErrors($validator);
		}
		
		$domainName = strtolower(trim(Input::get('name')));
		$domainIpPort = trim(Input::get('ipPort'));
		$domainAlias = Domain::formatAlias(trim(Input::get('alias', '')));
		$domainActivate = (int) Input::get('activated', 0);
		
		// Create domain
		$status = \Libraries\Sadeghi85\Domains::create(
			array(
				'domain'   => $domainName,
				'ipPort'   => $domainIpPort,
				'alias'    => $domainAlias,
				'activate' => $domainActivate,
			)
		);
		
		if ($status['status'] !== 0)
		{
			$errorMessage = (isset($status['message']) and $status['message']) ? $status['message'] : '';
			$errorCode = (isset($status['line']) and $status['line']) ? $status['line'] : '';
			$errorOutput = (isset($status['output']) and $status['output']) ? $status['output'] : '';
			
			// Log
			$currentUserUsername = Sentry::getUser()->usernameWithFullName();
			$myLog = new MyLog;
			$myLog->insertLog(
				array(
						'description' => sprintf('Code %s: %s%sDetails:%s%s', $errorCode, $errorMessage, "\r\n\r\n", "\r\n\r\n", $errorOutput),
						'user_id'     => Sentry::getUser()->id,
						'domain_id'   => null,
						'event'       => 'Create Domain',
						'type'        => 'danger',
				)
			);
				
			$error = sprintf('Code %s: %s', $errorCode, $errorMessage);
			
			$error .= ($errorOutput ? sprintf(' <br><br><pre><code>%s</code></pre>', $errorOutput) : '');
			
			// Redirect to the user creation page
			return Redirect::back()->withInput()->with('error', $error);
		}
		
		try
		{
			// Register domain in database
			$domain = new Domain;
			
			$domain->name = $domainName;
			//$domain->ip_port = $domainIpPort;
			$domain->alias = $domainAlias;
			$domain->activated = $domainActivate;
			
			$domain->save();
		}
		catch (\Exception $e)
		{
			// Log
			$domainNameToLog = $domain->name;
			$myLog = new MyLog;
			$myLog->insertLog(
				array(
						'description' => sprintf('Domain [%s] is created, but couldn\'t register it in the database.', $domainNameToLog),
						'user_id'     => Sentry::getUser()->id,
						'domain_id'   => null,
						'event'       => 'Create Domain',
						'type'        => 'danger',
				)
			);
				
			$error = sprintf('Domain [%s] is created, but couldn\'t register it in the database.', $domainNameToLog);
			return Redirect::back()->withInput()->with('error', $error);
		}
		
		if (Group::isRoot())
		{
			$users = Input::get('users', array());
			
			foreach ($users as $userId)
			{
				try
				{
					$domain->users()->attach($userId);
				}
				catch (\Exception $e)
				{
				
				}
			}
		}
		else
		{
			$domain->users()->attach(Sentry::getUser()->id);
		}
		
		// Log
		$domainNameToLog = $domain->name;
		$currentUserUsername = Sentry::getUser()->usernameWithFullName();
		$myLog = new MyLog;
		$myLog->insertLog(
			array(
					'description' => sprintf('User [%s] has created the Domain [%s].%sCurrent Status:%s%s', $currentUserUsername, $domainNameToLog, "\r\n\r\n", "\r\n\r\n", print_r($domain->toArray(), true)),
					'user_id'     => Sentry::getUser()->id,
					'domain_id'   => $domain->id,
					'event'       => 'Create Domain',
					'type'        => 'info',
			)
		);
				
		// Prepare the success message
		$success = Lang::get('domains/messages.success.create');

		// Redirect to the user page
		return Redirect::route('domains.index', array('page' => input::get('indexPage', 1)))->with('success', $success);
		
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if ( ! (Group::isRoot() or Sentry::getUser()->hasAccess('domain.edit')))
		{
			App::abort(403);
		}
		
		
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
		if ( ! (Group::isRoot() or Sentry::getUser()->hasAccess('domain.delete')))
		{
			App::abort(403);
		}
		
	}

}