@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('users/messages.index.title') :: @parent
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
		@lang('users/messages.index.header')

		<div class="pull-right">
			<a href="{{ route('users.create') }}" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> @lang('users/messages.index.create')</a>
		</div>
	</h3>
</div>

<div class="row">
	<div class="col-md-offset-21 col-md-15">
		<ul class="nav nav-pills nav-stacked">
			<li class="active">
				<a href="#">
				<span class="badge pull-right">{{ User::count() }}</span>
				Total
				</a>
			</li>
		</ul>
	</div>
	
	<div class="col-md-15">
		<ul class="nav nav-pills nav-stacked">
			<li class="active">
			<a href="#">
			<span class="badge pull-right">{{ User::activated()->count() }}</span>
			Active
			</a>
			</li>
		</ul>
	</div>
</div>

{{ $users->links() }}

<div class="table-responsive clearfix">

<table class="table table-hover table-striped table-curved">
	  	<thead>
          <tr>
            <th style="width: 50px !important;text-align: center;">@lang('users/messages.index.id')</th>
			<th style="width: 80px;text-align: center;">@lang('users/messages.index.activated')</th>
			<th>@lang('users/messages.index.username')</th>
			<th>@lang('users/messages.index.name')</th>

			<th style="width: 150px;">@lang('users/messages.index.actions')</th>
          </tr>
      </thead>
	<tbody>
		@if ($users->count() >= 1)
		@foreach ($users as $user)
		<tr>
			<td style="text-align: center;">{{ $user->id }}</td>
			<td style="text-align: center;"><span class="glyphicon glyphicon-{{ ($user->activated ? 'ok'
					 : 'remove')
					}}"></span></td>
			<td>{{ $user->username }}</td>
			<td>{{ $user->fullName() }}</td>
			
			<td>
				{{ Form::open(array('route' => array('users.destroy', $user->id), 'method' => 'DELETE', 'id' => 'delete'.$user->id, 'name' => 'User: '.$user->username)) }}
					
					<a href="{{ route('users.show', $user->id) }}" class="btn btn-xs btn-default">@lang('button.show')</a>
					
					@if (Sentry::getId() !== $user->id and $user->username !== 'root')
						<a href="{{ route('users.edit', $user->id) }}" class="btn btn-xs btn-default">@lang('button.edit')</a>
						<button type="button" class="btn btn-xs btn-danger">@lang('button.delete')</button>
					@else
						<span class="btn btn-xs btn-default disabled">@lang('button.edit')</span>
						<span class="btn btn-xs btn-danger disabled">@lang('button.delete')</span>
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
{{ $users->links() }}

<!-- Delete Warning Modal -->
@include('partials/delete_warning_modal')

@stop
