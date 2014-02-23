@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('groups/messages.edit.title') :: @parent
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
		@lang('groups/messages.edit.header')

		<a href="{{ route('groups.index') }}" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-circle-arrow-left"></i> @lang('groups/messages.edit.back')</a>
	</h3>
</div>

{{ Form::open(array('route' => array('groups.update', $group->id), 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'form', 'autocomplete' => 'off')) }}

	<div class="panel panel-primary">
		<div class="panel-heading">
			<span class="glyphicon glyphicon-collapse-up pull-right"></span>
			<h3 class="panel-title">General</h3>
		</div>
		<div class="panel-body collapse in">

			<p>&nbsp;</p>

			<fieldset>
				<!-- Name -->
				<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
					{{ Form::label('name', Lang::get('groups/messages.edit.name').' *',
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						{{ Form::text('name', Input::old('name', $group->name), array('class'=>'form-control')) }}
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('name') }}</span>
					</div>
				</div>

				<p class="help-block">Fields with asterisk (*) are required.</p>
			</fieldset>
		</div>
	</div>
	
	<div class="panel panel-primary">
		<div class="panel-heading">
			<span class="glyphicon glyphicon-collapse-up pull-right"></span>
			<h3 class="panel-title">Permissions</h3>
		</div>
		<div class="panel-body collapse in">

			<p>&nbsp;</p>

			@foreach ($allPermissions as $area => $permissions)
				<legend>{{ $area }}</legend>
				
				<fieldset>
					@foreach ($permissions as $permission)
					
						<!-- Multiple Radios (inline) -->
						<div class="form-group">
							{{ Form::label($permission['label'], $permission['label'], array('class' => 'col-md-12 control-label')) }}

							@php
								$_allowed = (array_get($selectedPermissions, $permission['permission']) == 1) ? true : false;
								$_denied = (array_get($selectedPermissions, $permission['permission']) == 0) ? true : false;
							@endphp
							
							<div class="col-md-24">
								<label class="radio-inline" for="{{ $permission['permission'] }}_allow">
									<input type="radio" value="1" id="{{ $permission['permission'] }}_allow" name="permissions[{{ $permission['permission'] }}]"{{ $_allowed ? ' checked="checked"' : '' }}>
									Allow
								</label>
								<label class="radio-inline" for="{{ $permission['permission'] }}_deny">
									<input type="radio" value="0" id="{{ $permission['permission'] }}_deny" name="permissions[{{ $permission['permission'] }}]"{{ $_denied ? ' checked="checked"' : '' }}>
									Deny
								</label>
							</div>
						</div>
					@endforeach
				</fieldset>
			@endforeach
		</div>
	</div>

<!-- Form Actions -->
<div class="form-group">
	<button type="reset" class="btn btn-default">Reset</button>
	<button type="submit" class="btn btn-primary">Edit Group</button>
</div>

{{ Form::close() }}

<!-- Collapse Panel -->
@include('partials/collapse_panel')

@stop