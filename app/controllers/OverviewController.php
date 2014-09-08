<?php

class OverviewController extends AuthorizedController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('app.overview', array(
			'hostName' => Libraries\Sadeghi85\Overview::getHostname(),
			'OS' => Libraries\Sadeghi85\Overview::getOperatingSystem(),
			'systemTime' => Libraries\Sadeghi85\Overview::getTime(),
			'uptime' => Libraries\Sadeghi85\Overview::getUptime(),
			'loadAverage' => Libraries\Sadeghi85\Overview::getLoadAverages(),
			'totalMemory' => Libraries\Sadeghi85\Overview::getTotalMemory(),
			'usedMemory' => Libraries\Sadeghi85\Overview::getUsedMemory(),
			'totalSwap' => Libraries\Sadeghi85\Overview::getTotalSwap(),
			'usedSwap' => Libraries\Sadeghi85\Overview::getUsedSwap(),
			'totalSpace' => Libraries\Sadeghi85\Overview::getTotalSpace(),
			'usedSpace' => Libraries\Sadeghi85\Overview::getUsedSpace(),
			'totalPanelSpace' => Libraries\Sadeghi85\Overview::getPanelTotalSpace(),
			'assignedPanelSpace' => Libraries\Sadeghi85\Overview::getPanelAssignedSpace(),
		
		));
	}

}