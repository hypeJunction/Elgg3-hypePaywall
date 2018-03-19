<?php

return [
	'field:object:blog:paywall' => 'Paid Access',

	'field:paid_access' => 'Paid Access',
	'field:paid_download' => 'Paid Download',

	'paywall:field:access_plans' => 'Members only access',
	'paywall:field:access_plans:help' => 'Require one of these plans to access the post',
	'paywall:field:access_price' => 'Paid Access',
	'paywall:field:access_price:help' => 'Allow users to purchase access to the post',

	'paywall:field:download_plans' => 'Members only download',
	'paywall:field:download_plans:help' => 'Require one of these plans to download the file',
	'paywall:field:download_price' => 'Paid Access',
	'paywall:field:download_price:help' => 'Allow users to purchase download to the file',

	'PostAccessException' => 'Access to this post is restricted to paying members',

	'paywall:access' => 'Get Access',
	'paywall:download' => 'Get Download Access',

	'paywall:module:subscribe' => 'Subscriber Access',
	'paywall:module:pay' => 'On-Demand Access',

	'paywall:payment:submit' => 'Pay',

	'paywall:paid_access:granted:subject' => 'Access to %s is granted',
	'paywall:paid_access:granted:message' => '
		Your payment was successful and you can now access %s.
		
		You can view the post at:
		%s
	',

	'paywall:paid_download:granted:subject' => 'Download access to %s is granted',
	'paywall:paid_download:granted:message' => '
		Your payment was successful and you can now download files from %s.
		
		You can view the post at:
		%s
	',
];