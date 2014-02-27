@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('accounts/messages.create.title') :: @parent
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
		@lang('accounts/messages.create.header')

		<a href="{{ route('accounts.index') }}" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-circle-arrow-left"></i> @lang('accounts/messages.create.back')</a>
	</h3>
</div>

{{ Form::open(array('route' => 'accounts.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form', 'autocomplete' => 'off')) }}

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
					{{ Form::label('username', Lang::get('accounts/messages.create.username').' *',
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
					{{ Form::label('password', Lang::get('accounts/messages.create.password').' *',
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

				<!-- Password Confirmation -->
				<div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
					{{ Form::label('password_confirmation', Lang::get('accounts/messages.create.password_confirmation').' *',
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

				<!-- Home -->
				<div class="form-group {{ $errors->has('home') ? 'has-error' : '' }}">
					{{ Form::label('home', Lang::get('accounts/messages.create.home').' *',
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						<div class="input-group">
									<span class="input-group-addon bg-primary">
										{{ Config::get('ftppanel.ftpHome').'/' }}
									</span>
							{{ Form::text('home', Input::old('home'), array('class'=>'form-control')) }}
						</div>
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('home') }}</span>
					</div>
				</div>

				<!-- IP -->
				<div class="form-group {{ $errors->has('ip') ? 'has-error' : '' }}">
					{{ Form::label('ip', Lang::get('accounts/messages.create.ip'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						{{ Form::textarea('ip', Input::old('ip'), array('class'=>'form-control',
						'placeholder'=>Lang::get('general.unlimited'))) }}
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('ip') }}</span>
					</div>
				</div>

				<!-- Upload Bandwidth -->
				<div class="form-group {{ $errors->has('ulbandwidth') ? 'has-error' : '' }}">
					{{ Form::label('ulbandwidth', Lang::get('accounts/messages.create.ulbandwidth'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-12">
						{{ Form::text('ulbandwidth', Input::old('ulbandwidth'), array('class'=>'form-control', 'placeholder' => Lang::get('general.unlimited'))) }}
					</div>
					<div class="col-md-6">
						<label class="control-label">KB/s</label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('ulbandwidth') }}</span>
					</div>
				</div>

				<!-- Download Bandwidth -->
				<div class="form-group {{ $errors->has('dlbandwidth') ? 'has-error' : '' }}">
					{{ Form::label('dlbandwidth', Lang::get('accounts/messages.create.dlbandwidth'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-12">
						{{ Form::text('dlbandwidth', Input::old('dlbandwidth'), array('class'=>'form-control', 'placeholder' => Lang::get('general.unlimited'))) }}
					</div>
					<div class="col-md-6">
						<label class="control-label">KB/s</label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('dlbandwidth') }}</span>
					</div>
				</div>

				<!-- Quota Size -->
				<div class="form-group {{ $errors->has('quotasize') ? 'has-error' : '' }}">
					{{ Form::label('quotasize', Lang::get('accounts/messages.create.quotasize'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-12">
						{{ Form::text('quotasize', Input::old('quotasize'), array('class'=>'form-control', 'placeholder' => Lang::get('general.unlimited'))) }}
					</div>
					<div class="col-md-6">
						<label class="control-label">MB</label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('quotasize') }}</span>
					</div>
				</div>

				<!-- Quota Files -->
				<div class="form-group {{ $errors->has('quotafiles') ? 'has-error' : '' }}">
					{{ Form::label('quotafiles', Lang::get('accounts/messages.create.quotafiles'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-12">
						{{ Form::text('quotafiles', Input::old('quotafiles'), array('class'=>'form-control', 'placeholder' => Lang::get('general.unlimited'))) }}
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('quotafiles') }}</span>
					</div>
				</div>

				<!-- Comment -->
				<div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }}">
					{{ Form::label('comment', Lang::get('accounts/messages.create.comment'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						{{ Form::textarea('comment', Input::old('comment'), array('class'=>'form-control')) }}
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('comment') }}</span>
					</div>
				</div>

				<!-- Activation Status -->
				<div class="form-group {{ $errors->has('activated') ? 'has-error' : '' }}">
					{{ Form::label('activated', Lang::get('accounts/messages.create.activated'),
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

@if (Sentry::getUser()->isSuperUser())
	<div class="panel panel-primary">
		<div class="panel-heading">
			<span class="glyphicon glyphicon-collapse-up pull-right"></span>
			<h3 class="panel-title">Users</h3>
		</div>
		<div class="panel-body collapse in">

			<p>&nbsp;</p>

			<fieldset>
				<!-- Users -->
				<div class="form-group {{ $errors->has('users') ? 'has-error' : '' }}">
					{{ Form::label('users', Lang::get('accounts/messages.create.users'),
					array('class' => 'control-label col-md-12')) }}
					<div class="col-md-24">
						<select name="users[]" id="users" multiple="multiple" class="form-control">
							@foreach ($allUsers as $user)
							<option value="{{ $user->id }}"{{ (in_array($user->id, $selectedUsers) ? ' selected="selected"' : '') }}>{{ $user->username }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-6">
						<label class="control-label"></label>
					</div>
					<div class="col-md-24">
						<span class="help-block">{{ $errors->first('users') }}</span>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
@endif

<!-- Form Actions -->
<div class="form-group">
	<button type="reset" class="btn btn-default">Reset</button>
	<button type="submit" class="btn btn-primary">Create Account</button>
</div>

{{ Form::close() }}

<!-- Collapse Panel -->
@include('partials/collapse_panel')

@stop