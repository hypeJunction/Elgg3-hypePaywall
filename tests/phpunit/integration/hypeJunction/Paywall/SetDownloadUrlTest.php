<?php

namespace hypeJunction\Paywall;

use Elgg\Event;
use Elgg\IntegrationTestCase;

class SetDownloadUrlTest extends IntegrationTestCase {

	public function getPluginID(): string {
		return 'hypepaywall';
	}

	public function up(): void {}

	public function down(): void {}

	private function makeEvent(\ElggEntity $entity): Event {
		$event = $this->getMockBuilder(Event::class)
			->disableOriginalConstructor()
			->getMock();
		$event->method('getEntityParam')->willReturn($entity);

		return $event;
	}

	public function testReturnsNullForNonFile(): void {
		$entity = $this->createObject(['subtype' => 'test_object']);
		$handler = new SetDownloadUrl();
		$result = $handler($this->makeEvent($entity));
		$this->assertNull($result);
	}
}
