<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('javascript')
@parent
	<script type="text/javascript">
		$( document ).ready(function() {
		
			setInterval(function() {
				$.get("{{ URL::route('keepalive') }}");
			}, 1000 * 60 * 5);
		});
	</script>
@stop