<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

<ul class="nav nav-tabs">
	<li class="{{ Request::is('*overview*') ? 'active' : '' }}"><a href="{{ URL::Route('overview.index') }}"><strong>Overview</strong></a></li>
	<li class="{{ Request::is('*accounts*') ? 'active' : '' }}"><a href="{{ URL::Route('accounts.index') }}"><strong>Accounts</strong></a></li>
	
@comment
	@if (Sentry::getUser()->hasAccess('log.*'))
	<li class="{{ Request::is('*logs*') ? 'active' : '' }}"><a href="{{ URL::Route('logs.index') }}"><strong>Logs</strong></a></li>
	@endif
@endcomment

	@if (Sentry::getUser()->isSuperUser())
		<li class="{{ Request::is('*users*') ? 'active' : '' }} nav-right"><a href="{{ URL::Route('users.index') }}"><strong>Users</strong></a></li>
		<li class="{{ Request::is('*groups*') ? 'active' : '' }} nav-right"><a href="{{ URL::Route('groups.index') }}"><strong>Groups</strong></a></li>
	@endif
</ul>