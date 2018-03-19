<?php

/**
 * Paywall
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 */
require_once __DIR__ . '/autoloader.php';

return function () {
	elgg_register_event_handler('init', 'system', function () {

		elgg_extend_view('elgg.css', 'paywall.css');
		
		elgg_register_plugin_hook_handler('fields', 'object', \hypeJunction\Paywall\AddFormField::class);

		elgg_register_plugin_hook_handler('gatekeeper', 'all', \hypeJunction\Paywall\PaidAccessGatekeeper::class);

		elgg_register_plugin_hook_handler('transaction:paid', 'payments', \hypeJunction\Paywall\ProcessSuccessfulTransaction::class);
		elgg_register_plugin_hook_handler('transaction:refunded', 'payments', \hypeJunction\Paywall\ProcessRefundedTransaction::class);

		elgg_register_plugin_hook_handler('download:url', 'file', \hypeJunction\Paywall\SetDownloadUrl::class);
	});
};
