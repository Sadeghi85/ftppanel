@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('logs/messages.show.title') :: @parent
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
		@lang('logs/messages.show.header')

		<a href="{{ route('logs.index') }}" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-circle-arrow-left"></i> @lang('logs/messages.show.back')</a>
	</h3>
</div>

<p>&nbsp;</p>

<div class="table-responsive">
	<table class="table table-hover table-striped table-curved">
		<thead>
		<tr>
			<th class="col-md-2">@lang('logs/messages.show.id')</th>
			<th class="col-md-9">@lang('logs/messages.show.site')</th>
			<th class="col-md-9">@lang('logs/messages.show.username')</th>
			<th class="col-md-7">@lang('logs/messages.show.event')</th>

			<th class="col-md-7">@lang('logs/messages.show.created_at')</th>
		</tr>
		</thead>
		<tbody>
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
				
				<td>{{ $log->created_at->diffForHumans() }}</td>
			</tr>
		</tbody>
	</table>
</div>

<p>&nbsp;</p>

<div class="table-responsive">
	<table class="table table-hover table-curved">
		<thead>
		<tr>
			<th class="col-md-72">@lang('logs/messages.show.description')</th>
		</tr>
		</thead>
		<tbody>
			<tr class="{{ $log->type }}">
				<td>{{ $log->description }}</td>
			</tr>
		</tbody>
	</table>
</div>


<p>&nbsp;</p>

<!-- Delete Warning Modal -->
@include('partials/delete_warning_modal')

@stop
