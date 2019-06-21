<?php

$entity = elgg_extract('entity', $vars);

if ($entity->paid_access_wall || $entity->paid_access_plans || $entity->paid_access_amount) {
	$info = elgg_view_icon('money-check-alt') . elgg_echo('paywall:access:walled');
	
	echo elgg_format_element('span', [
		'class' => 'elgg-listing-paywall elgg-state elgg-state-warning',
	], $info);
}

if ($entity->paid_download_wall || $entity->paid_download_plans || $entity->paid_download_amount) {
	$info = elgg_view_icon('money-check-alt') . elgg_echo('paywall:download:walled');

	echo elgg_format_element('span', [
		'class' => 'elgg-listing-paywall elgg-state elgg-state-warning',
	], $info);
}