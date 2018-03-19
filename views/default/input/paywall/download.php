<?php

$name = elgg_extract('name', $vars);
$value = elgg_extract('value', $vars, []);

$fields = [
	[
		'#type' => 'paywall/plans',
		'#label' => elgg_echo('paywall:field:download_plans'),
		'#help' => elgg_echo('paywall:field:download_plans:help'),
		'name' => "{$name}[plans]",
		'value' => elgg_extract('plans', $value),
	],
	[
		'#type' => 'payments/amount',
		'#label' => elgg_echo('paywall:field:download_price'),
		'#help' => elgg_echo('paywall:field:download_price:help'),
		'name' => "{$name}[price]",
		'value' => elgg_extract('price', $value),
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

