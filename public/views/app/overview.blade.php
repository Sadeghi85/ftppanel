@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('title')
	@lang('overview/messages.title') :: @parent
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
<div class="row">
	<div class="col-md-72">
		Total Accounts: {{ Account::count() }}
		<br>
		Active Accounts: {{ Account::activated()->count() }}
		<br><br>

		Ftp Status:
		<div class="ftp-status">
			<pre>
				<code>
					{{ Libraries\Sadeghi85\Overview::getFtpStatus() }}
				</code>
			</pre>
		</div>
	</div>
</div>
@stop
