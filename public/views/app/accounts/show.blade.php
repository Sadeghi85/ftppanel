@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('accounts/messages.show.title') :: @parent
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
		@lang('accounts/messages.show.header', array('id' => $account->id))

		<a href="{{ route('accounts.index') }}" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-circle-arrow-left"></i> @lang('accounts/messages.show.back')</a>
	</h3>
</div>

<p>&nbsp;</p>

<div class="table-responsive">
	<table class="table table-hover table-striped table-curved">
		<thead>
		<tr>
			<th style="width: 200px;">@lang('accounts/messages.show.key')</th>
			<th >@lang('accounts/messages.show.value')</th>
		</tr>
		</thead>
		<tbody>
			<tr>
				<td>@lang('accounts/messages.show.id')</td>
				<td>{{ $account->id }}</td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.activated')</td>
				<td><span class="glyphicon glyphicon-{{ ($account->activated ? 'ok'
					 : 'remove')
					}}"></span></td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.username')</td>
				<td>{{ $account->username }}</td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.home')</td>
				<td><span class="label label-primary">{{ Config::get('ftppanel.ftpHome') }}</span><span class="label label-success">{{ str_replace(Config::get('ftppanel.ftpHome'), '', $account->home) }}</span></td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.comment')</td>
				<td>
					@if ($account->comment)
						<pre><code>{{{ $account->comment }}}</code></pre>
					@endif
				</td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.ip')</td>
				<td>
					@if ($account->ip)
						<pre><code>{{ (Ip::formatForHumans($account->ip) ?: Lang::get('general.unlimited')) }}</code></pre>
					@endif
				</td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.ulbandwidth')</td>
				<td>
					@if ($account->ulbandwidth)
						{{ $account->ulbandwidth }} KB/s
					@else
						@lang('general.unlimited')
					@endif
				</td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.dlbandwidth')</td>
				<td>
					@if ($account->dlbandwidth)
						{{ $account->dlbandwidth }} KB/s
					@else
						@lang('general.unlimited')
					@endif
				</td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.quotasize')</td>
				<td>
					@if ($account->quotasize)
						{{ $account->quotasize }} MB
					@else
						@lang('general.unlimited')
					@endif
				</td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.quotafiles')</td>
				<td>
					@if ($account->quotafiles)
						{{ $account->quotafiles }}
					@else
						@lang('general.unlimited')
					@endif
				</td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.created_at')</td>
				<td>{{ $account->created_at->diffForHumans() }}</td>
			</tr>
			<tr>
				<td>@lang('accounts/messages.show.updated_at')</td>
				<td>{{ $account->updated_at->diffForHumans() }}</td>
			</tr>


		</tbody>
	</table>
</div>

<p>&nbsp;</p>

@stop
