<?php

namespace hypeJunction\Paywall;

use Elgg\Hook;
use hypeJunction\Payments\Amount;

class SetDownloadUrl {

	/**
	 * Rewrite download URL
	 *
	 * @param Hook $hook Hook
	 *
	 * @return bool
	 */
	public function __invoke(Hook $hook) {

		$file = $hook->getEntityParam();

		if (!$file instanceof \ElggFile) {
			return null;
		}

		if ($file->getVolatileData('allow_download')) {
			return null;
		}

		$paywall_enabled = function (\ElggEntity $entity) use (&$paywall_enabled) {
			$plans = (array) $entity->paid_download_plans;
			$amount = new Amount((int) $entity->paid_download_amount, $entity->paid_download_currency);

			if (!empty($plans) || !empty($amount->getAmount())) {
				return true;
			}

			$owner = $entity->getOwnerEntity();
			if ($owner && $paywall_enabled($owner)) {
				return true;
			}

			$container = $entity->getContainerEntity();
			if ($container && $paywall_enabled($container)) {
				return true;
			}
		};

		if ($paywall_enabled($file)) {
			return elgg_generate_url('paywall:download', [
				'guid' => $file->guid,
			]);
		}

	}
}