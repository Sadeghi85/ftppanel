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
	<div class="col-md-offset-12 col-md-48">
		
		<div class="panel panel-primary" style="line-height: 28px;">
		<div class="panel-body">
			
			<div class="row"><div class="col-md-22"><strong>System hostname</strong></div><div class="col-md-50">{{ $hostName }}</div></div>
		
			<div class="row"><div class="col-md-22"><strong>Operating system</strong></div><div class="col-md-50">{{ $OS }}</div></div>
			
			<div class="row"><div class="col-md-22"><strong>Time on system</strong></div><div class="col-md-50">{{ $systemTime }}</div></div>
			
			<div class="row"><div class="col-md-22"><strong>System uptime</strong></div><div class="col-md-50">{{ $uptime }}</div></div>
			
			<div class="row"><div class="col-md-22"><strong>CPU load averages</strong></div><div class="col-md-50">{{ $loadAverage }}</div></div>
			
			<div class="row"><div class="col-md-22"><strong>Real memory</strong></div><div class="col-md-50">
			
				{{ $totalMemory }} MB total, {{ $usedMemory }} MB used

				<div class="progress" style="margin-bottom:0px;">
					<div class="progress-bar progress-bar-danger" style="width:{{ $usedMemory / $totalMemory * 100 }}%">
					</div>
					<div class="progress-bar progress-bar" style="width: {{ 100 - $usedMemory / $totalMemory * 100 }}%">
					</div>
				</div>

			</div></div>
			
			<div class="row"><div class="col-md-22"><strong>Virtual memory</strong></div><div class="col-md-50">
			
				{{ $totalSwap }} MB total, {{ $usedSwap }} MB used

				<div class="progress" style="margin-bottom:0px;">
					<div class="progress-bar progress-bar-danger" style="width:{{ $usedSwap / $totalSwap * 100 }}%">
					</div>
					<div class="progress-bar progress-bar" style="width: {{ 100 - $usedSwap / $totalSwap * 100 }}%">
					</div>
				</div>

			</div></div>
			
			
			<div class="row"><div class="col-md-22"><strong>Local disk space</strong></div><div class="col-md-50">
			
				{{ $totalSpace }} GB total, {{ $usedSpace }} GB used

				<div class="progress" style="margin-bottom:0px;">
					<div class="progress-bar progress-bar-danger" style="width:{{ $usedSpace / $totalSpace * 100 }}%">
					</div>
					<div class="progress-bar progress-bar" style="width: {{ 100 - $usedSpace / $totalSpace * 100 }}%">
					</div>
				</div>

			</div></div>
			
			<div class="row"><div class="col-md-22"><strong>Panel assigned disk space</strong></div><div class="col-md-50">
			
				{{ $totalPanelSpace }} GB panel usable physical space, {{ $assignedPanelSpace }} GB panel assigned

				<div class="progress" style="margin-bottom:0px;">
					<div class="progress-bar progress-bar-danger" style="width:{{ $assignedPanelSpace / $totalPanelSpace * 100 }}%">
					</div>
					<div class="progress-bar progress-bar" style="width: {{ 100 - $assignedPanelSpace / $totalPanelSpace * 100 }}%">
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
