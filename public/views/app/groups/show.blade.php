@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('groups/messages.show.title') :: @parent
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
		@lang('groups/messages.show.header', array('id' => $group->id))

		<a href="{{ route('groups.index') }}" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-circle-arrow-left"></i> @lang('groups/messages.show.back')</a>
	</h3>
</div>

<p>&nbsp;</p>

<div class="table-responsive">
	<table class="table table-hover table-striped table-curved">
		<thead>
			<tr>
				<th style="width: 200px;">@lang('groups/messages.show.key')</th>
				<th >@lang('groups/messages.show.value')</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>@lang('groups/messages.show.id')</td>
				<td>{{ $group->id }}</td>
			</tr>
			
			<tr>
				<td>@lang('groups/messages.show.name')</td>
				<td>{{ $group->name }}</td>
			</tr>
			
			<tr>
				<td>@lang('groups/messages.show.permissions')</td>
				<td>
					<table style="width: 400px;" class="table table-hover table-striped table-curved">
						<thead>
							<tr>
								<th>@lang('groups/messages.show.resource')</th>
								<th>@lang('groups/messages.show.permission')</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($allPermissions as $area => $permissions)
								@foreach ($permissions as $permission)
									@if (array_get($selectedPermissions, $permission['permission']) == 1)
										<tr>
											<td>{{ $area }}</td>
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
				<td>@lang('groups/messages.show.created_at')</td>
				<td>{{ $group->created_at->diffForHumans() }}</td>
			</tr>
			<tr>
				<td>@lang('groups/messages.show.updated_at')</td>
				<td>{{ $group->updated_at->diffForHumans() }}</td>
			</tr>


		</tbody>
	</table>
</div>

<p>&nbsp;</p>

@stop
