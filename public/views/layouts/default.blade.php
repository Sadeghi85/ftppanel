<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="@lang('app.title')">

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

  <title>
    @section('title')
          {{ Lang::get('app.title') }}
    @show
  </title>

    <!-- Bootstrap -->
    <link href="{{ asset('/assets/css/bootstrap.css') }}" rel="stylesheet" media="screen">
    <link href="{{ asset('/assets/css/bootstrap-theme.css') }}" rel="stylesheet" media="screen">

	<link href="{{ asset('/assets/css/multi-select.css') }}" rel="stylesheet" media="screen">
    <link href="{{ asset('/assets/css/bootstrap-extra.css') }}" rel="stylesheet" media="screen">
	

 
@section('style') 
   <style type="text/css">
   
   </style>
@show

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="{{ asset('/assets/js/html5shiv.js') }}"></script>
      <script src="{{ asset('/assets/js/respond.min.js') }}"></script>
    <![endif]-->
</head>

<body>

@section('navbar')
 <div class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">{{ Lang::get('app.title') }}</a>
    </div>

    <div class="navbar-collapse collapse">
		<ul class="nav navbar-nav">
		
	@comment
		<li class="{{ Route::currentRouteName() == 'profile.index' ? 'active' : '' }}">
			<a href="{{ URL::Route('profile.index') }}">{{ Lang::get('profile/messages.profile') }}</a>
		</li>
	@endcomment
	
		<li><a href="{{ URL::Route('auth.logout') }}">{{ Lang::get('auth/messages.logout.logout') }}</a></li>
		</ul>
    </div><!--/.nav-collapse -->


   </div>
 </div>
@show

@section('container')
<div class="my-fluid-container">
	<div class="row">
		<div class="col-md-72">
			<div class="box">
			
				@if(Sentry::check())
					@include('partials/tabs')
				@endif
				
				<div class="row">
					<div class="col-md-72">
						<!-- Notifications -->
						@include('partials/notifications')

						<!-- Content -->
						@yield('content')
					</div>
				</div>
			</div><!--/.box-->
		</div><!--/.col-->
	</div><!--/.row-->
</div><!--/.container-->

@include('partials/keep_alive')
@show

@section('javascript')
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('/assets/js/jquery-1.10.2.min.js') }}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('/assets/js/jquery.multi-select.js') }}"></script>

	<script type="text/javascript">
		$( document ).ready(function() {
		
			$('select[multiple]').multiSelect({
				selectableHeader: '<span class="label label-primary select-multiple-header">Selectable items</span>',
				selectionHeader: '<span class="label label-primary select-multiple-header">Selected items</span>',
			});
		});
		
	</script>
@show

</body>
</html>