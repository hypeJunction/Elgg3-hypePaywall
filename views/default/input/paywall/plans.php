<?php

if (!elgg_is_active_plugin('hypeSubscriptions')) {
	return;
}

$svc = elgg()->subscriptions;
/* @var $svc \hypeJunction\Subscriptions\SubscriptionsService */

$page_owner = elgg_get_page_owner_entity();

$group_plans = [];
$site_plans = $svc->getPlans()->get(0);

if ($page_owner instanceof ElggGroup) {
	$group_plans = $svc->getPlans($page_owner)->get(0);
}

$plans = array_merge($site_plans, $group_plans);
/* @var $plans \hypeJunction\Subscriptions\SubscriptionPlan[] */

if (empty($plans)) {
	return;
}

$options = [];

foreach ($plans as $plan) {
	$options[$plan->guid] = $plan->getDisplayName();
}

$vars['options'] = array_flip($options);
$vars['default'] = false;

echo elgg_view('input/checkboxes', $vars);