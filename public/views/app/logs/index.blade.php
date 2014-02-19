@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('logs/messages.index.title') :: @parent
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
		@lang('logs/messages.index.header')

	</h3>
</div>

{{ $logs->links() }}

<div class="table-responsive">

<table class="table table-hover table-striped table-curved">
	  	<thead>
          <tr>
            <th class="col-md-2">@lang('logs/messages.index.id')</th>
			<th class="col-md-9">@lang('logs/messages.index.site')</th>
			<th class="col-md-9">@lang('logs/messages.index.username')</th>
			<th class="col-md-7">@lang('logs/messages.index.event')</th>
			<th class="col-md-20">@lang('logs/messages.index.description')</th>
			
			<th class="col-md-7">@lang('logs/messages.index.created_at')</th>
			<th class="col-md-7">@lang('logs/messages.index.actions')</th>
          </tr>
      </thead>
	<tbody>
		@if ($logs->count() >= 1)
			@foreach ($logs as $log)
				<tr class="{{ $log->type }}">
					<td>{{ $log->id }}</td>
					
					<td>
						@if ($log->site)
							{{ $log->site->name }}
						@endif
					</td>
					
					<td>
						@if ($log->user)
							{{ $log->user->username . (trim($log->user->fullName()) ? sprintf(' (%s)', trim($log->user->fullName())) : '') }}
						@endif
					</td>

					<td>{{ $log->event }}</td>
					<td>{{ Str::words(strip_tags($log->description), 10) }}</td>
					
					<td>{{ $log->created_at->diffForHumans() }}</td>
					<td>
						{{ Form::open(array('route' => array('logs.destroy', $log->id), 'method' => 'DELETE', 'id' => 'delete'.$log->id, 'name' => 'Log: '.$log->id)) }}
							
							
							<a href="{{ route('logs.show', $log->id) }}" class="btn btn-xs btn-default">@lang('button.show')</a>
							
							@if (Sentry::getUser()->inGroup(Sentry::findGroupByName('Root')))
								<button type="button" class="btn btn-xs btn-danger">@lang('button.delete')</button>
							@endif

						{{ Form::close() }}
					</td>
				</tr>
			@endforeach
		@else
		<tr>
			<td colspan="5">No results</td>
		</tr>
		@endif
	</tbody>
</table>
</div>
{{ $logs->links() }}

<!-- Delete Warning Modal -->
@include('partials/delete_warning_modal')

@stop
