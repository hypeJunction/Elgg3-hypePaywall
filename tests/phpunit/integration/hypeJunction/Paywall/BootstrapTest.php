<?php

namespace hypeJunction\Paywall;

use Elgg\IntegrationTestCase;

class BootstrapTest extends IntegrationTestCase {

	public function getPluginID(): string {
		return 'hypepaywall';
	}

	public function up(): void {}

	public function down(): void {}

	public function testPluginIsActive(): void {
		$plugin = elgg_get_plugin_from_id('hypepaywall');
		$this->assertInstanceOf(\ElggPlugin::class, $plugin);
		$this->assertTrue($plugin->isActive());
	}

	public function testBootstrapClassResolves(): void {
		$plugin = elgg_get_plugin_from_id('hypepaywall');
		$bootstrap = $plugin->getBootstrap();
		$this->assertInstanceOf(Bootstrap::class, $bootstrap);
	}

	public function testFieldsEventHandlerExists(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('fields', 'object'));
	}

	public function testGatekeeperEventHandlerExists(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('gatekeeper', 'all'));
	}

	public function testDownloadUrlEventHandlerExists(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('download:url', 'file'));
	}

	public function testPermissionsCheckDownloadEventHandlerExists(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('permissions_check:download', 'all'));
	}

	public function testTransactionPaidEventHandlerExists(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('transaction:paid', 'payments'));
	}

	public function testTransactionRefundedEventHandlerExists(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('transaction:refunded', 'payments'));
	}

	public function testPaywallDownloadRouteIsRegistered(): void {
		$url = elgg_generate_url('paywall:download', ['guid' => 1]);
		$this->assertNotEmpty($url);
	}

	public function testPaywallDownloadActionIsRegistered(): void {
		$actions = _elgg_services()->actions->getAllActions();
		$this->assertArrayHasKey('paywall/pay/access', $actions);
		$this->assertArrayHasKey('paywall/pay/download', $actions);
	}
}
