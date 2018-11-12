<?php

namespace hypeJunction\Paywall;

use Elgg\Database\QueryBuilder;
use Elgg\Hook;
use hypeJunction\Payments\Amount;
use hypeJunction\Subscriptions\SubscriptionsService;

class CanDownloadPermission {

	use Downloadable;

	/**
	 * Override download permission
	 *
	 * @param Hook $hook Hook
	 *
	 * @return bool
	 * @throws \DataFormatException
	 */
	public function __invoke(Hook $hook) {

		$file = $hook->getEntityParam();

		$entity = $this->resolvePaywalledEntity($file);
		$user = $hook->getUserParam();

		if (!$entity) {
			return true;
		}

		$plans = elgg_is_active_plugin('hypeSubscriptions') ? (array) $entity->paid_download_plans : null;
		$amount = new Amount((int) $entity->paid_download_amount, $entity->paid_download_currency);

		if (empty($plans) && empty($amount->getAmount())) {
			return null;
		}

		if (!$user) {
			return null;
		}

		if ($entity->canEdit($user->guid)) {
			return true;
		}

		$owner = $entity->getOwnerEntity();
		if ($owner && $owner->canEdit($user->guid)) {
			return true;
		}

		if (elgg_is_active_plugin('hypeSubscriptions')) {
			$svc = SubscriptionsService::instance();
			/* @var $svc \hypeJunction\Subscriptions\SubscriptionsService */

			if ($plans) {
				$has_plan = $svc->getSubscriptions(null, $plans, ['count' => true]);

				if ($has_plan) {
					return true;
				}
			}
		}

		if ($user) {
			if (check_entity_relationship($user->guid, 'paid_download', $entity->guid)) {
				return true;
			}
		}

		return false;
	}
}