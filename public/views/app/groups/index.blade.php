@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('groups/messages.index.title') :: @parent
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
		@lang('groups/messages.index.header')

		<div class="pull-right">
			<a href="{{ route('groups.create') }}" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> @lang('groups/messages.index.create')</a>
		</div>
	</h3>
</div>

{{ $groups->links() }}

<div class="table-responsive clearfix">

<table class="table table-hover table-striped table-curved">
	  	<thead>
          <tr>
            <th style="width: 50px;text-align: center;">@lang('groups/messages.index.id')</th>
			<th >@lang('groups/messages.index.name')</th>
			<th style="width: 100px;">@lang('groups/messages.index.users')</th>
			<th style="width: 150px;">@lang('groups/messages.index.actions')</th>
          </tr>
      </thead>
	<tbody>
		@if ($groups->count() >= 1)
			@foreach ($groups as $group)
				<tr>
					<td style="text-align: center;">{{ $group->id }}</td>
					<td>{{ $group->name }}</td>
					<td style="text-align: center;">{{ $group->users()->count() }}</td>
					<td>
						{{ Form::open(array('route' => array('groups.destroy', $group->id), 'method' => 'DELETE', 'id' => 'delete'.$group->id, 'name' => 'Group: '.$group->name)) }}
							
							<a href="{{ route('groups.show', $group->id) }}" class="btn btn-xs btn-default">@lang('button.show')</a>
							
							@if ($group->name !== 'Root')
								<a href="{{ route('groups.edit', $group->id) }}" class="btn btn-xs btn-default">@lang('button.edit')</a>
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

		</tr>
		@endif
	</tbody>
</table>
</div>
{{ $groups->links() }}

<!-- Delete Warning Modal -->
@include('partials/delete_warning_modal')

@stop
