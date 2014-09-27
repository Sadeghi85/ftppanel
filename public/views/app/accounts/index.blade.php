@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('accounts/messages.index.title') :: @parent
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
		@lang('accounts/messages.index.header')

		<div class="pull-right">
			@if (Sentry::getUser()->hasAccess('account.create'))
				<a href="{{ route('accounts.create') }}" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> @lang('accounts/messages.index.create')</a>
			@else
				<span class="btn btn-sm btn-primary disabled"><i class="glyphicon glyphicon-plus-sign"></i> @lang('accounts/messages.index.create')</span>
			@endif
		</div>
		
	</h3>
</div>

<div class="row">
	<div class="col-md-offset-21 col-md-15">
		<ul class="nav nav-pills nav-stacked">
			<li class="active">
				<a href="#">
				<span class="badge pull-right">{{ Account::count() }}</span>
				Total
				</a>
			</li>
		</ul>
	</div>
	
	<div class="col-md-15">
		<ul class="nav nav-pills nav-stacked">
			<li class="active">
			<a href="#">
			<span class="badge pull-right">{{ Account::activated()->count() }}</span>
			Active
			</a>
			</li>
		</ul>
	</div>
</div>


{{ $accounts->links() }}

<div class="table-responsive clearfix">

<table class="table table-hover table-striped table-curved">
	<thead>
		<tr>
			<th style="width: 50px;text-align: center;">@lang('accounts/messages.index.id')</th>
			<th style="width: 80px;text-align: center;">@lang('accounts/messages.index.activated')</th>
			<th style="width: 100px;text-align: center;">@lang('accounts/messages.index.readonly')</th>
			<th style="width: 280px;text-align: center;">@lang('accounts/messages.index.quotasize')</th>
			<th style="width: 150px;">@lang('accounts/messages.index.username')</th>
			<th >@lang('accounts/messages.index.home')</th>

			<th style="width: 150px;">@lang('accounts/messages.index.actions')</th>
		</tr>
	  </thead>
	<tbody>
		@if ($accounts->count() >= 1)
			@foreach ($accounts as $account)
				<tr>
					<td style="text-align: center;">{{ $account->id }}</td>
					<td style="text-align: center;"><span class="glyphicon glyphicon-{{ ($account->activated ? 'ok'
					 : 'remove')
					}}"></span></td>
					<td style="text-align: center;"><span class="glyphicon glyphicon-{{ ($account->readonly ? 'ok'
					 : 'remove')
					}}"></span></td>
					@php
						$usedSpace = Libraries\Sadeghi85\Overview::getDirSpace($account->home);
					@endphp
					<td style="text-align: center;">{{ $account->quotasize }} / {{ $usedSpace }} used
						@if ($account->quotasize > 0)
						<div class="progress" style="margin-bottom:0px;">
							<div class="progress-bar progress-bar-danger" style="width:{{ $usedSpace / $account->quotasize * 100 }}%">
							</div>
							<div class="progress-bar progress-bar" style="width: {{ 100 - $usedSpace / $account->quotasize * 100 }}%">
							</div>
						</div>
						@endif
					
					</td>
					<td>{{ $account->username }}</td>

					<td><span class="label label-primary">{{ Config::get('ftppanel.ftpHome') }}</span><span class="label label-success">{{ str_replace(Config::get('ftppanel.ftpHome'), '', $account->home) }}</span></td>

					<td>
						{{ Form::open(array('route' => array('accounts.destroy', $account->id), 'method' => 'DELETE', 'id' => 'delete'.$account->id, 'name' => 'Account: '.$account->username)) }}

							<a href="{{ route('accounts.show', $account->id) }}" class="btn btn-xs btn-default">@lang('button.show')</a>

							@if (Sentry::getUser()->hasAccess('account.edit'))
								<a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-xs btn-default">@lang('button.edit')</a>
							@else
								<span class="btn btn-xs btn-default disabled">@lang('button.edit')</span>
							@endif
							
							@if (Sentry::getUser()->hasAccess('account.delete'))
								<button type="button" class="btn btn-xs btn-danger">@lang('button.delete')</button>
							@else
								<span class="btn btn-xs btn-danger disabled">@lang('button.delete')</span>
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
{{ $accounts->links() }}

<!-- Delete Warning Modal -->
@include('partials/delete_warning_modal')

@stop
