<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>
<div class="modal fade"  id="delete_warning_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Delete <span class="label label-danger"></span></h4>
      </div>
      <div class="modal-body">
        <p>Do you want to delete this item?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger">Delete</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@section('javascript')
@parent
	<script type="text/javascript">
		$( document ).ready(function() {
		
			$(document).on('click', 'form button.btn-danger', function ( event ) {

				$('body').data('delete_form_id', $(this).parent().get(0).id);
				
				$('#delete_warning_modal .modal-title span').text($(this).parent().get(0).name);

				$('#delete_warning_modal').modal('show');

			});
			
			$(document).on('click', '#delete_warning_modal button.btn-danger', function ( event ) {

				var delete_form_id = $('body').data('delete_form_id');
				
				$('body').removeData('delete_form_id');
				
				$('#delete_warning_modal').modal('hide');
				
				$('#' + delete_form_id).submit();
				
			});
		});
	</script>
@stop