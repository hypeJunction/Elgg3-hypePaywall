<?php

namespace hypeJunction\Paywall;

use DataFormatException;
use Elgg\Hook;
use hypeJunction\Payments\Amount;
use function PasswordCompat\binary\check;

class PaidAccessGatekeeper {

	/**
	 * Restrict entity access
	 * @elgg_plugin_hook gatekeeper all
	 *
	 * @param Hook $hook Hook
	 *
	 * @return void
	 * @throws DataFormatException
	 * @throws PostAccessException
	 */
	public function __invoke(Hook $hook) {

		$entity = $hook->getEntityParam();

		$plans = (array) $entity->paid_access_plans;
		$amount = new Amount((int) $entity->paid_access_amount, $entity->paid_access_currency);

		if (empty($plans) && empty($amount->getAmount())) {
			return;
		}

		$owner = $entity->getOwnerEntity();
		if ($owner->canEdit()) {
			return;
		}

		if ($plans) {
			$svc = elgg()->subscriptions;
			/* @var $svc \hypeJunction\Subscriptions\SubscriptionsService */

			$has_plan = $svc->getSubscriptions(null, $plans, ['count' => true]);

			if ($has_plan) {
				return;
			}
		}

		$user = elgg_get_logged_in_user_entity();
		if (check_entity_relationship($user->guid, 'paid_access', $entity->guid)) {
			return;
		}

		$exception = new PostAccessException();
		$exception->setParams([
			'entity' => $entity,
		]);
		$exception->setRedirectUrl(elgg_generate_url('paywall:pay:access', [
			'guid' => $entity->guid,
		]));

		throw $exception;
	}
}