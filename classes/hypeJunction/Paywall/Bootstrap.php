<?php

namespace hypeJunction\Paywall;

use Elgg\Includer;
use Elgg\PluginBootstrap;
use hypeJunction\Paywall\AddFormField;
use hypeJunction\Paywall\CanDownloadPermission;
use hypeJunction\Paywall\PaidAccessGatekeeper;
use hypeJunction\Paywall\ProcessRefundedTransaction;
use hypeJunction\Paywall\ProcessSuccessfulTransaction;
use hypeJunction\Paywall\SetDownloadUrl;

class Bootstrap extends PluginBootstrap {

	/**
	 * Get plugin root
	 * @return string
	 */
	protected function getRoot() {
		return dirname(dirname(dirname(dirname(__FILE__))));
	}

	/**
	 * {@inheritdoc}
	 */
	public function load() {
		Includer::requireFileOnce($this->getRoot() . '/autoloader.php');
	}

	/**
	 * {@inheritdoc}
	 */
	public function boot() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		elgg_extend_view('elements/components.css', 'paywall.css');

		elgg_register_plugin_hook_handler('fields', 'object', AddFormField::class);

		elgg_register_plugin_hook_handler('gatekeeper', 'all', PaidAccessGatekeeper::class);

		elgg_register_plugin_hook_handler('transaction:paid', 'payments', ProcessSuccessfulTransaction::class);
		elgg_register_plugin_hook_handler('transaction:refunded', 'payments', ProcessRefundedTransaction::class);

		elgg_register_plugin_hook_handler('download:url', 'file', SetDownloadUrl::class, 900);

		elgg_register_plugin_hook_handler('permissions_check:download', 'all', CanDownloadPermission::class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function ready() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function shutdown() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function activate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function deactivate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function upgrade() {

	}

}