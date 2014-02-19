<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>
<p>&nbsp;</p>

@if ($errors->any())
<div class="alert alert-danger alert-dismissable fade in">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<h4>Error</h4>
	Please check the form below for errors
</div>
@endif

@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissable fade in">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<h4>Success</h4>
	{{ $message }}
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissable fade in">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<h4>Error</h4>
	{{ $message }}
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-dismissable fade in">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<h4>Warning</h4>
	{{ $message }}
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-dismissable fade in">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<h4>Info</h4>
	{{ $message }}
</div>
@endif

@section('javascript')
@parent
	<script type="text/javascript">
		$( document ).ready(function() {
			
			window.setTimeout(function() {
				$(".alert-success").alert('close');
			}, 5000);
			
		});
	</script>
@stop
