@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('panel_logs/messages.index.title') :: @parent
@stop

@section('style')
@parent
	<style type="text/css">

	</style>
@stop

@section('javascript')
@parent
	<script type="text/javascript">

	</script>
@stop

@section('content')
<div class="page-header">
	<h3>
		@lang('panel_logs/messages.index.header')

	</h3>
</div>

{{ $logs->links() }}

<div class="table-responsive clearfix">

<table class="table table-hover table-striped table-curved">
	  	<thead>
          <tr>
            <th style="width: 50px !important;text-align: center;">@lang('panel_logs/messages.index.id')</th>
			<th >@lang('panel_logs/messages.index.username')</th>
			<th >@lang('panel_logs/messages.index.event')</th>
			<th >@lang('panel_logs/messages.index.account')</th>
			<th style="width: 150px !important;">@lang('panel_logs/messages.index.created_at')</th>
			<th style="width: 150px;">@lang('panel_logs/messages.index.actions')</th>
          </tr>
      </thead>
	<tbody>
		@if ($logs->count() >= 1)
			@foreach ($logs as $log)
				<tr class="{{ $log->type }}">
					<td style="text-align: center;">{{ $log->id }}</td>

					<td>
						@if ($log->user)
							@php
								$user = unserialize($log->user_object);
								if (is_object($user) and $user->username != $log->user->username) {
									echo sprintf('%s (%s)', $log->user->username, $user->username);
								}
								else {
									echo sprintf('%s', $log->user->username);
								}
							@endphp
						@endif
					</td>

					<td>{{ Lang::get('panel_logs/messages.events.'.Config::get('panel_log.log_actions.'.$log->event, 0)) }}</td>
					
					<td>
						@if ($log->account)
							@php
								$account = unserialize($log->account_object);
								if (is_object($account) and $account->username != $log->account->username) {
									echo sprintf('%s (%s)', $log->account->username, $account->username);
								}
								else {
									echo sprintf('%s', $log->account->username);
								}
							@endphp
						@endif
					</td>
					
					<td>{{ $log->created_at->diffForHumans() }}</td>
					
					<td>
						{{ Form::open(array('route' => array('logs.destroy', $log->id), 'method' => 'DELETE', 'id' => 'delete'.$log->id, 'name' => 'Log: '.$log->id)) }}
							
							
							<a href="{{ route('logs.show', $log->id) }}" class="btn btn-xs btn-default">@lang('button.show')</a>
							
							@if (Sentry::getUser()->isSuperUser())
								<button type="button" class="btn btn-xs btn-danger">@lang('button.delete')</button>
							@endif

						{{ Form::close() }}
					</td>
				</tr>
			@endforeach
		@else
			<tr>
				
			</tr>
		@endif
	</tbody>
</table>
</div>
{{ $logs->links() }}

<!-- Delete Warning Modal -->
@include('partials/delete_warning_modal')

@stop
