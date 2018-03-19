<?php

$guid = elgg_extract('guid', $vars);
$entity = get_entity($guid);

$title = elgg_echo('paywall:access');

elgg_push_entity_breadcrumbs($entity, true);
elgg_push_breadcrumb($title);

$content = elgg_view('paywall/pay/access', [
	'entity' => $entity,
]);

$layout = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $layout);