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

		<div class="pull-right">
			<a href="{{ route('groups.index') }}" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-circle-arrow-left"></i> @lang('groups/messages.edit.back')</a>
		</div>
	</h3>
</div>

{{ Form::open(array('route' => array('groups.update', $group->id), 'method' => 'PUT', 'class' => '', 'id' => 'form', 'autocomplete' => 'off')) }}

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">General</h3>
	</div>
	<div class="panel-body">
		
		<p>&nbsp;</p>
		
		<div class="row">
			<div class="col-md-36">
				<!-- Text input-->
				<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
					<fieldset class="form-inline">
						<div class="row">
							<div class="">
								<label class="control-label" for="name">@lang('groups/messages.edit.name')</label>
							</div>

							<div class="col-md-32">
								{{ Form::text('name', Input::old('name', $group->name), array('class'=>'form-control')) }}
								<p class="help-block">{{ $errors->first('name') }}</p>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Permissions</h3>
	</div>
	<div class="panel-body">
		
		<p>&nbsp;</p>

		<div class="row">
			<div class="col-md-36">
				<div class="form-group">
					@foreach ($permissions as $area => $permissions)
					<fieldset class="form-inline">
						<legend>{{ $area }}</legend>

						@foreach ($permissions as $permission)
							<div class="row">
								<div class="">
									<label class="control-label">{{ $permission['label'] }}</label>
								</div>

								<div class="col-md-32">
									<div class="radio inline">
										<label for="{{ $permission['permission'] }}_allow" onclick="">
											<input type="radio" value="1" id="{{ $permission['permission'] }}_allow" name="permissions[{{ $permission['permission'] }}]"{{ (array_get($selectedPermissions, $permission['permission']) === 1 ? ' checked="checked"' : '') }}>
											Allow
										</label>
									</div>
									
									&nbsp;
									
									<div class="radio inline">
										<label for="{{ $permission['permission'] }}_deny" onclick="">
											<input type="radio" value="0" id="{{ $permission['permission'] }}_deny" name="permissions[{{ $permission['permission'] }}]"{{ ( ! array_get($selectedPermissions, $permission['permission']) ? ' checked="checked"' : '') }}>
											Deny
										</label>
									</div>
								</div>
							</div>
						@endforeach
					</fieldset>
					
					<p>&nbsp;</p>
					
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Form Actions -->
<div class="form-group">
	<button type="reset" class="btn btn-default">Reset</button>
	<button type="submit" class="btn btn-primary">Edit Group</button>
</div>

{{ Form::close() }}

@stop