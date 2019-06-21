<?php

return [
	'field:object:blog:paywall' => 'Paid Access',

	'field:paid_access' => 'Paid Access',
	'field:paid_download' => 'Paid Download',

	'paywall:field:paywalled_access' => 'Enable paywall',
	'paywall:field:paywalled_access:help' => 'Restrict access to users with an active site subscription',

	'paywall:field:paywalled_downloads' => 'Enable paywall',
	'paywall:field:paywalled_downloads:help' => 'Restrict downloads to users with an active site subscription',

	'paywall:field:access_plans' => 'Additional access plans',
	'paywall:field:access_plans:help' => 'Also grant access to users with these plans to access the post',
	'paywall:field:access_price' => 'Paid Access',
	'paywall:field:access_price:help' => 'Allow users to purchase access to the post',

	'paywall:field:download_plans' => 'Additional access plans',
	'paywall:field:download_plans:help' => 'Also grant access to users with these plans to download the file',
	'paywall:field:download_price' => 'Paid Download',
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

	'paywall:access:walled' => 'Premium Content',
	'paywall:download:walled' => 'Premium Download',
];