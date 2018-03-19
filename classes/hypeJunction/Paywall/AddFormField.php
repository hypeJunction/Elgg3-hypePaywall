<?php

namespace hypeJunction\Paywall;

use hypeJunction\Payments\Amount;

class AddFormField {

	/**
	 * Add paywall fields
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Hook $hook) {

		$fields = $hook->getValue();

		$fields['paid_access'] = [
			'#type' => 'paywall/access',
			'#getter' => function (\ElggEntity $entity) {
				if (!$entity->paid_access_plans && !$entity->paid_access_amount && !$entity->paid_access_currency) {
					return null;
				}

				return [
					'plans' => $entity->paid_access_plans,
					'price' => new Amount((int) $entity->paid_access_amount, $entity->paid_access_currency),
				];
			},
			'#setter' => function (\ElggEntity $entity, $value) {
				$plans = elgg_extract('plans', $value);
				$price = elgg_extract('price', $value);
				$amount = elgg_extract('amount', $price, '0');
				$currency = elgg_extract('currency', $price);

				$amount = Amount::fromString($amount, $currency);

				$entity->paid_access_plans = $plans;
				$entity->paid_access_amount = $amount->getAmount();
				$entity->paid_access_currency = $amount->getCurrency();
			},
			'#visibility' => function (\ElggEntity $entity) use ($hook) {
				$can_sell = elgg_is_admin_logged_in();

				$params = ['entity' => $entity];
				$can_sell = elgg_trigger_plugin_hook('permissions_check:sell', 'object', $params, $can_sell);

				if (!$can_sell) {
					return false;
				}

				$params = [
					'entity' => $entity,
				];

				return $hook->elgg()->hooks->trigger(
					'uses:paid_access',
					"$entity->type:$entity->subtype",
					$params,
					true
				);
			},
			'#section' => 'sidebar',
			'#profile' => false,
		];

		$fields['paid_download'] = [
			'#type' => 'paywall/download',
			'#getter' => function (\ElggEntity $entity) {
				if (!$entity->paid_download_plans && !$entity->paid_download_amount && !$entity->paid_download_currency) {
					return null;
				}

				return [
					'plans' => $entity->paid_download_plans,
					'price' => new Amount((int) $entity->paid_download_amount, $entity->paid_download_currency),
				];
			},
			'#setter' => function (\ElggEntity $entity, $value) {
				$plans = elgg_extract('plans', $value);
				$price = elgg_extract('price', $value);
				$amount = elgg_extract('amount', $price, '0');
				$currency = elgg_extract('currency', $price);

				$amount = Amount::fromString($amount, $currency);

				$entity->paid_download_plans = $plans;
				$entity->paid_download_amount = $amount->getAmount();
				$entity->paid_download_currency = $amount->getCurrency();
			},
			'#visibility' => function (\ElggEntity $entity) use ($hook) {
				$can_sell = elgg_is_admin_logged_in();

				$params = ['entity' => $entity];
				$can_sell = elgg_trigger_plugin_hook('permissions_check:sell', 'object', $params, $can_sell);

				if (!$can_sell) {
					return false;
				}

				$params = [
					'entity' => $entity,
				];

				return $hook->elgg()->hooks->trigger(
					'uses:paid_download',
					"$entity->type:$entity->subtype",
					$params,
					true
				);
			},
			'#section' => 'sidebar',
			'#profile' => false,
		];

		return $fields;
	}
}
