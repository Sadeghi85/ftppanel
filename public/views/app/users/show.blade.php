@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('users/messages.show.title') :: @parent
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
		@lang('users/messages.show.header', array('id' => $user->id))

		<a href="{{ route('users.index') }}" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-circle-arrow-left"></i> @lang('users/messages.show.back')</a>
	</h3>
</div>

<p>&nbsp;</p>

<div class="table-responsive">
	<table class="table table-hover table-striped table-curved">
		<thead>
			<tr>
				<th style="width: 200px;">@lang('users/messages.show.key')</th>
				<th >@lang('users/messages.show.value')</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>@lang('users/messages.show.id')</td>
				<td>{{ $user->id }}</td>
			</tr>
			
			<tr>
				<td>@lang('users/messages.show.activated')</td>
				<td><span class="glyphicon glyphicon-{{ ($user->activated ? 'ok'
					 : 'remove')
					}}"></span></td>
			</tr>
			
			<tr>
				<td>@lang('users/messages.show.username')</td>
				<td>{{ $user->username }}</td>
			</tr>
			
			<tr>
				<td>@lang('users/messages.show.first_name')</td>
				<td>{{ $user->first_name }}</td>
			</tr>
			
			<tr>
				<td>@lang('users/messages.show.last_name')</td>
				<td>{{ $user->last_name }}</td>
			</tr>
			
			<tr>
				<td>@lang('users/messages.show.groups')</td>
				<td>
					@foreach($groups as $group)
						<span class="label label-primary">{{ $group }}</span><span class="label">&nbsp;</span>
					@endforeach
				</td>
			</tr>
			
			<tr>
				<td>@lang('users/messages.show.permissions')</td>
				<td>
					<table style="width: 300px;" class="table table-hover table-striped table-curved">
						<thead>
							<tr>
								<th>@lang('users/messages.show.resource')</th>
								<th>@lang('users/messages.show.permission')</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($allPermissions as $area => $permissions)
								@foreach ($permissions as $permission)
									@if (array_get($selectedPermissions, $permission['permission']) == 1)
										<tr>
											<td>{{ $area}}</td>
											<td>{{ $permission['label'] }}</td>
										</tr>
									@endif
								@endforeach
							@endforeach
						</tbody>
					</table>
				</td>
			</tr>
			
			<tr>
				<td>@lang('users/messages.show.created_at')</td>
				<td>{{ $user->created_at->diffForHumans() }}</td>
			</tr>
			<tr>
				<td>@lang('users/messages.show.updated_at')</td>
				<td>{{ $user->updated_at->diffForHumans() }}</td>
			</tr>

		</tbody>
	</table>
</div>

<p>&nbsp;</p>

@stop
