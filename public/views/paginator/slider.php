<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>
<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

<div class="">
	<ul class="pagination pull-left">
		<li>
		Showing
		<?php echo $paginator->getFrom(); ?>
		-
		<?php echo $paginator->getTo(); ?>
		of
		<?php echo $paginator->getTotal(); ?>
		items
		</li>
	</ul>

	<ul class="pagination pull-right">
		<?php echo $presenter->render(); ?>
	</ul>
</div>
