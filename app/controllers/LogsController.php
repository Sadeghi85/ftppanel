<?php

class LogsController extends AuthorizedController {

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
		// $item_ids = DB::table('category_item')->where('category_id', '=', $category_id)->get('item_id');
		// $item_ids = array_map(function($arr){ return $arr->item_id; }, $item_ids);
		// $items = Item::where_in('id', $item_ids)->paginate(10);

		
		if (Group::isRoot())
		{
			// Grab all the logs
			$logs = MyLog::newest()->with('site', 'user')->paginate();
		}
		else
		{
			if (Sentry::getUser()->hasAccess('log.all'))
			{
				// Grab all the logs including Root
				$logs = MyLog::newest()->with('site', 'user')->paginate();
			}
			elseif (Sentry::getUser()->hasAccess('log.nonroot'))
			{
				// Grab all the logs for users that belong to groups other than Root
				$groupIDs = Sentry::getGroupProvider()->createModel()->where('name', '<>', 'Root')->lists('id');
				$userIDs = DB::table('users_groups')->whereIn('group_id', $groupIDs)->lists('user_id');
				$logs = MyLog::whereIn('user_id', $userIDs)->newest()->with('site', 'user')->paginate();
			}
			elseif (Sentry::getUser()->hasAccess('log.self'))
			{
				// Grab all the logs for this user only
				$logs = MyLog::where('user_id', '=', Sentry::getUser()->id)->newest()->with('site', 'user')->paginate();
			}
			else
			{
				App::abort(403);
			}
		}
		
		// Show the page
		return View::make('app.logs.index', compact('logs'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if (Group::isRoot())
		{
			// Grab the log
			$log = MyLog::where('id', '=', $id)->with('site', 'user')->first();
		}
		else
		{
			if (Sentry::getUser()->hasAccess('log.all'))
			{
				// Grab the log, including Root
				$log = MyLog::where('id', '=', $id)->with('site', 'user')->first();
			}
			elseif (Sentry::getUser()->hasAccess('log.nonroot'))
			{
				// Grab the log for users that belong to groups other than Root
				$groupIDs = Sentry::getGroupProvider()->createModel()->where('name', '<>', 'Root')->lists('id');
				$userIDs = DB::table('users_groups')->whereIn('group_id', $groupIDs)->lists('user_id');
				$log = MyLog::where('id', '=', $id)->whereIn('user_id', $userIDs)->with('site', 'user')->first();
			}
			elseif (Sentry::getUser()->hasAccess('log.self'))
			{
				// Grab the log for this user only
				$log = MyLog::where('id', '=', $id)->where('user_id', '=', Sentry::getUser()->id)->with('site', 'user')->first();
			}
			else
			{
				App::abort(403);
			}
		}
		
		if ( ! $log)
		{
			return Redirect::route('logs.index')->with('error', Lang::get('logs/messages.error.log_not_found', compact('id')));
		}

		// Show the page
		return View::make('app.logs.show', compact('log'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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
		if ( ! Group::isRoot())
		{
			App::abort(403);
		}
		
		$log = MyLog::findOrFail($id);

		// Delete the log
		$log->delete();

		// Prepare the success message
		$success = Lang::get('logs/messages.success.delete');

		// Redirect to the logs page
		return Redirect::back()->with('success', $success);
	}

}