<?php

namespace hypeJunction\Paywall;

use ElggEntity;
use hypeJunction\Fields\Field;
use hypeJunction\Payments\Amount;
use Symfony\Component\HttpFoundation\ParameterBag;

class PaidAccessField extends Field {

	/**
	 * {@inheritdoc}
	 */
	public function isVisible(ElggEntity $entity, $context = null) {
		if (!parent::isVisible($entity, $context)) {
			return false;
		}

		$can_sell = elgg_is_admin_logged_in();

		$params = ['entity' => $entity];
		$can_sell = elgg_trigger_plugin_hook('permissions_check:sell', 'object', $params, $can_sell);

		if (!$can_sell) {
			return false;
		}

		$params = [
			'entity' => $entity,
		];

		return elgg()->hooks->trigger(
			'uses:paid_access',
			"$entity->type:$entity->subtype",
			$params,
			true
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function save(ElggEntity $entity, ParameterBag $parameters) {
		$value = $parameters->get($this->name);

		$plans = elgg_extract('plans', $value);
		$price = elgg_extract('price', $value);
		$amount = elgg_extract('amount', $price, '0');
		$currency = elgg_extract('currency', $price);

		$amount = Amount::fromString($amount, $currency);

		$entity->paid_access_plans = $plans;
		$entity->paid_access_amount = $amount->getAmount();
		$entity->paid_access_currency = $amount->getCurrency();
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve(ElggEntity $entity) {
		if (!$entity->paid_access_plans && !$entity->paid_access_amount && !$entity->paid_access_currency) {
			return null;
		}

		return [
			'plans' => $entity->paid_access_plans,
			'price' => new Amount((int) $entity->paid_access_amount, $entity->paid_access_currency),
		];
	}
}