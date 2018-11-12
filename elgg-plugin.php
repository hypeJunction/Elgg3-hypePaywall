<?php

return [
	'bootstrap' => \hypeJunction\Paywall\Bootstrap::class,

	'actions' => [
		'paywall/pay/access' => [
			'controller' => \hypeJunction\Paywall\AccessPaymentAction::class,
		],
		'paywall/pay/download' => [
			'controller' => \hypeJunction\Paywall\DownloadPaymentAction::class,
		],
	],

	'routes' => [
		'paywall:pay:access' => [
			'path' => '/paywall/pay/access/{guid}',
			'resource' => 'paywall/pay/access',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'paywall:pay:download' => [
			'path' => '/paywall/pay/download/{guid}',
			'resource' => 'paywall/pay/download',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'paywall:download' => [
			'path' => '/paywall/download/{guid}',
			'controller' => \hypeJunction\Paywall\DownloadController::class,
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
];
