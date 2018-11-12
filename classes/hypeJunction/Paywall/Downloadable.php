<?php

namespace hypeJunction\Paywall;

use hypeJunction\Payments\Amount;

trait Downloadable {

	/**
	 * Check if paywal is enabled on entity
	 *
	 * @param \ElggEntity $entity Entity
	 *
	 * @return bool
	 */
	protected function isPaywallEnabled(\ElggEntity $entity) {
		if ($this->resolvePaywalledEntity($entity)) {
			return true;
		}

		return false;
	}

	/**
	 * Loop through entity hierarchy to find paywalled entity
	 *
	 * @param \ElggEntity $entity
	 *
	 * @return \ElggEntity
	 */
	protected function resolvePaywalledEntity(\ElggEntity $entity) {
		$plans = (array) $entity->paid_download_plans;
		$amount = new Amount((int) $entity->paid_download_amount, $entity->paid_download_currency);

		if (!empty($plans) || !empty($amount->getAmount())) {
			return $entity;
		}

		$owner = $entity->getOwnerEntity();
		if ($owner && $this->resolvePaywalledEntity($owner)) {
			return $owner;
		}

		$container = $entity->getContainerEntity();
		if ($container && $this->resolvePaywalledEntity($container)) {
			return $container;
		}
	}

	/**
	 * Download a file
	 *
	 * @param \ElggFile $file
	 *
	 * @return \Elgg\Http\RedirectResponse
	 */
	public function download(\ElggFile $file) {
		$file->setVolatileData('allow_download', true);

		$user = elgg_get_logged_in_user_entity();

		$log = serialize([
			'user_guid' => $user->guid,
			'ip_address' => _elgg_services()->request->getClientIp(),
		]);

		$file->annotate('log:download', $log, ACCESS_PUBLIC, $user->guid);

		elgg_trigger_event('download', 'file', $file);

		return elgg_redirect_response($file->getDownloadURL());
	}

	/**
	 * Get payment form URL
	 *
	 * @param \ElggFile $file File
	 *
	 * @return false|string
	 */
	public function getPaymentUrl(\ElggFile $file) {
		return elgg_generate_url('paywall:pay:download', [
			'guid' => $this->resolvePaywalledEntity($file)->guid,
		]);
	}
}