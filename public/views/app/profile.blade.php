@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	{{ Lang::get('profile/messages.title') }} | @parent
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

@stop
