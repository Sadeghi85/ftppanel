@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('users/messages.create.title') :: @parent
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
		@lang('users/messages.create.header')

		<a href="{{ route('users.index') }}" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-circle-arrow-left"></i> @lang('users/messages.create.back')</a>
	</h3>
</div>

{{ Form::open(array('route' => 'users.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form', 'autocomplete' => 'off')) }}

	{{ Form::hidden('indexPage', Input::old('indexPage', (isset($indexPage) ? $indexPage : 1)), array('class'=>'form-control')) }}
	
	<div class="panel panel-primary">
		<div class="panel-heading">
			<span class="glyphicon glyphicon-collapse-up pull-right"></span>
			<h3 class="panel-title">General</h3>
		</div>
		<div class="panel-body collapse in">

			<p>&nbsp;</p>

			<fieldset>
				<!-- Username -->
				<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
					{{ Form::label('username', Lang::get('users/messages.create.username').' *',
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						{{ Form::text('username', Input::old('username'), array('class'=>'form-control')) }}
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('username') }}</span>
					</div>
				</div>
				
				<!-- Password -->
				<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
					{{ Form::label('password', Lang::get('users/messages.create.password').' *',
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						{{ Form::password('password', array('class'=>'form-control')) }}
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('password') }}</span>
					</div>
				</div>
				
				<!-- Password Confirm -->
				<div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
					{{ Form::label('password_confirmation', Lang::get('users/messages.create.password_confirmation').' *',
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						{{ Form::password('password_confirmation', array('class'=>'form-control')) }}
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('password_confirmation') }}</span>
					</div>
				</div>
				
				<!-- First Name -->
				<div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
					{{ Form::label('first_name', Lang::get('users/messages.create.first_name'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						{{ Form::text('first_name', Input::old('first_name'), array('class'=>'form-control')) }}
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('first_name') }}</span>
					</div>
				</div>
				
				<!-- Last Name -->
				<div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
					{{ Form::label('last_name', Lang::get('users/messages.create.last_name'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						{{ Form::text('last_name', Input::old('last_name'), array('class'=>'form-control')) }}
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('last_name') }}</span>
					</div>
				</div>
				
				<!-- Activation Status -->
				<div class="form-group {{ $errors->has('activated') ? 'has-error' : '' }}">
					{{ Form::label('activated', Lang::get('users/messages.create.activated'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-12">
						{{ Form::select('activated', array('0'=>Lang::get('general.no'),'1'=>Lang::get('general.yes')), Input::old('activated', 0), array('class'=>'form-control')) }}
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('activated') }}</span>
					</div>
				</div>

				<p class="help-block">Fields with asterisk (*) are required.</p>
			</fieldset>
		</div>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<span class="glyphicon glyphicon-collapse-up pull-right"></span>
			<h3 class="panel-title">Groups</h3>
		</div>
		<div class="panel-body collapse in">

			<p>&nbsp;</p>

			<fieldset>
				<!-- Groups -->
				<div class="form-group {{ $errors->has('groups') ? 'has-error' : '' }}">
					{{ Form::label('users', Lang::get('users/messages.create.groups'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						<select name="groups[]" id="groups" multiple="multiple" class="form-control">
							@foreach ($allGroups as $group)
							<option value="{{ $group->id }}"{{ in_array($group->id, $selectedGroups) ? ' selected="selected"' : '' }}>{{ $group->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('groups') }}</span>
					</div>
				</div>
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
								
								$_denied  = (array_get($selectedPermissions, $permission['permission']) == -1) ? true : false;
								
								$_inherited = (array_get($selectedPermissions, $permission['permission']) == 0) ? true : false;
							@endphp
							
							<div class="col-md-24">
								<label class="radio-inline" for="{{ $permission['permission'] }}_allow">
									<input type="radio" value="1" id="{{ $permission['permission'] }}_allow" name="permissions[{{ $permission['permission'] }}]"{{ $_allowed ? ' checked="checked"' : '' }}>
									Allow
								</label>
								
								<label class="radio-inline" for="{{ $permission['permission'] }}_deny">
									<input type="radio" value="-1" id="{{ $permission['permission'] }}_deny" name="permissions[{{ $permission['permission'] }}]"{{ $_denied ? ' checked="checked"' : '' }}>
									Deny
								</label>
								
								@if ($permission['can_inherit'])
								<label class="radio-inline" for="{{ $permission['permission'] }}_inherit">
									<input type="radio" value="0" id="{{ $permission['permission'] }}_inherit" name="permissions[{{ $permission['permission'] }}]"{{ $_inherited ? ' checked="checked"' : '' }}>
									Inherit
								</label>
								@endif
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
	<button type="submit" class="btn btn-primary">Create User</button>
</div>

{{ Form::close() }}

<!-- Collapse Panel -->
@include('partials/collapse_panel')

@stop