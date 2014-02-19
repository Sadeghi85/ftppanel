<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('javascript')
@parent
	<script type="text/javascript">
		$( document ).ready(function() {
		
			$(document).on('click', '.panel-heading', function ( event ) {

				var span = $(this).children('span').first();

				if (span.hasClass('glyphicon-collapse-up')) {
					span.removeClass('glyphicon-collapse-up').addClass('glyphicon-collapse-down');
				}
				else {
					span.removeClass('glyphicon-collapse-down').addClass('glyphicon-collapse-up');
				}

				$(this).parent().children('.panel-body').first().collapse('toggle');

			});
		});
	</script>
@stop