<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_entity($entity, [
	'full_view' => false,
	'class' => 'paywall-entity-preview',
]);

$plan_guids = (array) $entity->paid_access_plans;
if ($entity->paid_access_wall) {
	$site_plan_guids = (array) elgg_get_config('subscriptions.site_membership_plans');
	$plan_guids = array_merge($plan_guids, $site_plan_guids);
}

$plans = array_map(function ($e) {
	return get_entity($e);
}, $plan_guids);

$plans = array_filter($plans);

$plans = array_filter($plans, function($p) {
	return !$p->internal_use;
});

if (!empty($plans)) {
	$subscribe = elgg_view_form('subscriptions/subscribe', [], [
		'plans' => $plans,
		'user' => elgg_get_logged_in_user_entity(),
	]);

	if ($subscribe) {
		$subscribe = elgg_view_module('info', elgg_echo('paywall:module:subscribe'), $subscribe);
		$subscribe = elgg_format_element('div', ['class' => 'elgg-col elgg-col1-of2'], $subscribe);
	}
}

$pay = elgg_view_form('paywall/pay/access', [], [
	'entity' => $entity,
]);

if ($pay) {
	$pay = elgg_view_module('info', elgg_echo('paywall:module:pay'), $pay);
	$pay = elgg_format_element('div', ['class' => 'elgg-col elgg-col1-of2'], $pay);
}

echo elgg_format_element('div', [
	'class' => 'elgg-columns paywall-payment-forms',
], $subscribe . $pay);