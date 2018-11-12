<?php

namespace hypeJunction\Paywall;

use Elgg\Cli\PluginsHelper;
use Elgg\Event;
use Elgg\Http\RedirectResponse;
use Elgg\UnitTestCase;
use hypeJunction\Subscriptions\Subscription;
use hypeJunction\Subscriptions\SubscriptionPlan;

class PaywallUnitTest extends UnitTestCase {

	use PluginsHelper;

	public function up() {
		$this->startPlugin(null, true, true);
	}

	public function down() {

	}

	public function testOverridesDownloadUrl() {
		$file = $this->createObject([
			'subtype' => 'file',
			'paid_download_amount' => 1000,
			'paid_download_currency' => 'USD',
		]);
		/* @var $file \ElggFile */

		$url = elgg_get_download_url($file);

		$this->assertEquals(
			elgg_normalize_site_url("paywall/download/$file->guid"),
			$url
		);
	}

	public function testDeniesDownloadPermission() {
		$owner = $this->createUser();

		$file = $this->createObject([
			'subtype' => 'file',
			'owner_guid' => $owner->guid,
			'paid_download_amount' => 1000,
			'paid_download_currency' => 'USD',
		]);
		/* @var $file \ElggFile */

		$user = $this->createUser();

		$this->assertTrue($file->canDownload($owner->guid));
		$this->assertFalse($file->canDownload($user->guid));
	}

	public function testGrantsDownloadPermissionAfterPayment() {
		$file = $this->createObject([
			'subtype' => 'file',
			'paid_download_amount' => 1000,
			'paid_download_currency' => 'USD',
		]);
		/* @var $file \ElggFile */

		$user = $this->createUser();

		add_entity_relationship($user->guid, 'paid_download', $file->guid);

		$this->assertTrue($file->canDownload($user->guid));
	}

	public function testGrantsDownloadPermissionAfterSubscription() {
		$plan = $this->createObject([
			'subtype' => SubscriptionPlan::SUBTYPE,
		]);
		/* @var $plan \hypeJunction\Subscriptions\SubscriptionPlan */

		$file = $this->createObject([
			'subtype' => 'file',
			'paid_download_plans' => [$plan->guid],
		]);
		/* @var $file \ElggFile */

		$user = $this->createUser();

		$this->assertInstanceOf(Subscription::class, $plan->subscribe($user));

		$this->assertTrue($file->canDownload($user->guid));
	}

	/**
	 * @expectedException \hypeJunction\Paywall\PostAccessException
	 */
	public function testDeniesDownloadRequestForInaccesibleFile() {
		$file = $this->createObject([
			'subtype' => 'file',
			'paid_download_amount' => 1000,
			'paid_download_currency' => 'USD',
		]);
		/* @var $file \ElggFile */

		$user = $this->createUser();
		elgg_get_session()->setLoggedInUser($user);

		$request = $this->prepareHttpRequest("paywall/download/$file->guid");

		try {
			_elgg_services()->router->getResponse($request);
		} catch (\Exception $ex) {
			elgg_get_session()->removeLoggedInUser();

			throw $ex;
		}
	}

	public function testDownloadsFileViaHttpRequest() {
		$calls = 0;

		$handler = function (Event $event) use (&$calls) {
			$calls++;
		};

		elgg_register_event_handler('download', 'file', $handler);

		$owner = $this->createUser();
		$file = $this->createObject([
			'subtype' => 'file',
			'owner_guid' => $owner->guid,
			'paid_download_amount' => 1000,
			'paid_download_currency' => 'USD',
		]);
		/* @var $file \ElggFile */

		$file->setFilename('test.txt');
		$file->open('write');
		$file->close();

		$user = $this->createUser();
		elgg_get_session()->setLoggedInUser($user);

		add_entity_relationship($user->guid, 'paid_download', $file->guid);

		$request = $this->prepareHttpRequest("paywall/download/$file->guid");

		$response = _elgg_services()->router->getResponse($request);
		/* @var $response \Elgg\Http\RedirectResponse */

		$this->assertInstanceOf(RedirectResponse::class, $response);

		$this->assertEquals(1, $calls);

		elgg_unregister_event_handler('download', 'file', $handler);

		elgg_get_session()->removeLoggedInUser();

		$file->delete();
	}
}