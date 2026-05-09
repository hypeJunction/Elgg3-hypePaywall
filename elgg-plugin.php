<?php

return [
	'plugin' => [
		'version' => '6.0.0',
	],

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

	'view_extensions' => [
		'elements/components.css' => [
			'paywall.css' => [],
		],
		'object/elements/imprint/contents' => [
			'paywall/imprint' => [],
		],
	],

	'events' => [
		'fields' => [
			'object' => [
				\hypeJunction\Paywall\AddFormField::class => [],
			],
		],
		'gatekeeper' => [
			'all' => [
				\hypeJunction\Paywall\PaidAccessGatekeeper::class => [],
			],
		],
		'transaction:paid' => [
			'payments' => [
				\hypeJunction\Paywall\ProcessSuccessfulTransaction::class => [],
			],
		],
		'transaction:refunded' => [
			'payments' => [
				\hypeJunction\Paywall\ProcessRefundedTransaction::class => [],
			],
		],
		'download:url' => [
			'file' => [
				\hypeJunction\Paywall\SetDownloadUrl::class => ['priority' => 900],
			],
		],
		'permissions_check:download' => [
			'all' => [
				\hypeJunction\Paywall\CanDownloadPermission::class => [],
			],
		],
	],
];
