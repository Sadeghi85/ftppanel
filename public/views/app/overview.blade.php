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
<br><br>
<div class="row" style="line-height: 190%;">
	<div class="col-md-offset-18 col-md-36">
		
		<div class="panel panel-primary">
		<div class="panel-body">
			
			<div class="row"><div class="col-md-22"><strong>System hostname</strong></div><div class="col-md-50">{{ Libraries\Sadeghi85\Overview::getHostname() }}</div></div>
		
			<div class="row"><div class="col-md-22"><strong>Operating system</strong></div><div class="col-md-50">{{ Libraries\Sadeghi85\Overview::getOperatingSystem() }}</div></div>
			
			<div class="row"><div class="col-md-22"><strong>Time on system</strong></div><div class="col-md-50">{{ Libraries\Sadeghi85\Overview::getTime() }}</div></div>
			
			<div class="row"><div class="col-md-22"><strong>System uptime</strong></div><div class="col-md-50">{{ Libraries\Sadeghi85\Overview::getUptime() }}</div></div>
			
			<div class="row"><div class="col-md-22"><strong>CPU load averages</strong></div><div class="col-md-50">{{ Libraries\Sadeghi85\Overview::getLoadAverages() }}</div></div>
			
			<div class="row"><div class="col-md-22"><strong>Real memory</strong></div><div class="col-md-50">
			
				{{ Libraries\Sadeghi85\Overview::getTotalMemory() }} MB total, {{ Libraries\Sadeghi85\Overview::getUsedMemory() }} MB used

				<div class="progress" style="margin-bottom:0px;">
					<div class="progress-bar progress-bar-danger" style="width:{{ Libraries\Sadeghi85\Overview::getUsedMemory() / Libraries\Sadeghi85\Overview::getTotalMemory() * 100 }}%">
					</div>
					<div class="progress-bar progress-bar" style="width: {{ 100 - Libraries\Sadeghi85\Overview::getUsedMemory() / Libraries\Sadeghi85\Overview::getTotalMemory() * 100 }}%">
					</div>
				</div>

			</div></div>
			
			<div class="row"><div class="col-md-22"><strong>Virtual memory</strong></div><div class="col-md-50">
			
				{{ Libraries\Sadeghi85\Overview::getTotalSwap() }} MB total, {{ Libraries\Sadeghi85\Overview::getUsedSwap() }} MB used

				<div class="progress" style="margin-bottom:0px;">
					<div class="progress-bar progress-bar-danger" style="width:{{ Libraries\Sadeghi85\Overview::getUsedSwap() / Libraries\Sadeghi85\Overview::getTotalSwap() * 100 }}%">
					</div>
					<div class="progress-bar progress-bar" style="width: {{ 100 - Libraries\Sadeghi85\Overview::getUsedSwap() / Libraries\Sadeghi85\Overview::getTotalSwap() * 100 }}%">
					</div>
				</div>

			</div></div>
			
			
			<div class="row"><div class="col-md-22"><strong>Local disk space</strong></div><div class="col-md-50">
			
				{{ Libraries\Sadeghi85\Overview::getTotalSpace() }} GB total, {{ Libraries\Sadeghi85\Overview::getUsedSpace() }} GB used

				<div class="progress" style="margin-bottom:0px;">
					<div class="progress-bar progress-bar-danger" style="width:{{ Libraries\Sadeghi85\Overview::getUsedSpace() / Libraries\Sadeghi85\Overview::getTotalSpace() * 100 }}%">
					</div>
					<div class="progress-bar progress-bar" style="width: {{ 100 - Libraries\Sadeghi85\Overview::getUsedSpace() / Libraries\Sadeghi85\Overview::getTotalSpace() * 100 }}%">
					</div>
				</div>

			</div></div>
			
			<div class="row"><div class="col-md-22"><strong>Panel assigned disk space</strong></div><div class="col-md-50">
			
				{{ Libraries\Sadeghi85\Overview::getTotalSpace() }} GB total, {{ Libraries\Sadeghi85\Overview::getPanelAssignedSpace() }} GB used

				<div class="progress" style="margin-bottom:0px;">
					<div class="progress-bar progress-bar-danger" style="width:{{ Libraries\Sadeghi85\Overview::getPanelAssignedSpace() / Libraries\Sadeghi85\Overview::getTotalSpace() * 100 }}%">
					</div>
					<div class="progress-bar progress-bar" style="width: {{ 100 - Libraries\Sadeghi85\Overview::getPanelAssignedSpace() / Libraries\Sadeghi85\Overview::getTotalSpace() * 100 }}%">
					</div>
				</div>

			</div></div>
			
		</div>
		</div>



		
	</div>
	
	<!--<div class="col-md-36">
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
	</div>-->
</div>
@stop
