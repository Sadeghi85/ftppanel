@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	{{ Lang::get('auth/messages.login.title') }} :: @parent
@stop

@section('style')
<style type="text/css">

</style>
@stop

@section('navbar')

@stop

@section('container')
<div class="container" style="margin-top:56px;">
	<div class="row">
		<div class="col-md-offset-18 col-md-36">
			{{ Form::open(array('route' => 'auth.login', 'class' => 'box form-horizontal', 'autocomplete' => 'off')) }}
				<fieldset>

					<!-- Login Form -->
					<legend><h3>{{ Lang::get('auth/messages.login.formname') }}</h3></legend>
					<p>&nbsp;</p>

					<!-- Username -->
					<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
						<label class="col-md-18 control-label" for="username">{{ Lang::get('auth/messages.login.username') }}</label>
						<div class="col-md-36">
							{{ Form::text('username', Input::old('username'), array('class'=>'form-control')) }}
							<span class="help-block">{{ $errors->first('username') }}</span>
						</div>
					</div>

					<!-- Password -->
					<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
						<label class="col-md-18 control-label" for="password">{{ Lang::get('auth/messages.login.password') }}</label>
						<div class="col-md-36">
							{{ Form::password('password', array('class'=>'form-control')) }}
							<span class="help-block">{{ $errors->first('password') }}</span>
						</div>
					</div>

					<!-- Remember Me -->
					<div class="form-group">
						<label class="col-md-18 control-label">{{ Lang::get('auth/messages.login.remember-me') }}</label>
						<div class="col-md-18">
							<label class="checkbox-inline" for="remember-me">
								{{ Form::checkbox('remember-me', 'remember-me') }}
							</label>
						</div>
					</div>

					<!-- Button -->
					<div class="form-group">
						<label class="col-md-18 control-label" for="submit">&nbsp;</label>
						<div class="col-md-18">
							{{ Form::submit(Lang::get('auth/messages.login.login'),
							array('class' => 'btn btn-primary')) }}
						</div>
					</div>

				</fieldset>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop