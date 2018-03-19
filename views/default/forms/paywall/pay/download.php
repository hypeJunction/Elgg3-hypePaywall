<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$amount = new \hypeJunction\Payments\Amount((int) $entity->paid_download_amount, $entity->paid_download_currency);
if (!$amount->getAmount()) {
	return;
}

$product = new \hypeJunction\Paywall\UnlimitedDownload();
$product->title = $entity->getDisplayName();
$product->post_guid = $entity->guid;
$product->setPrice($amount);
$order = new \hypeJunction\Payments\Order();
$order->add($product, 1);

$transaction = new \hypeJunction\Payments\Transaction();
$transaction->setOrder($order);
$transaction->owner_guid = $site->guid;
$transaction->container_guid = $site->guid;
$transaction->access_id = ACCESS_PRIVATE;

$transaction->store();

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'transaction_id',
	'value' => $transaction->transaction_id,
]);

echo elgg_view('payments/order', [
	'order' => $order,
]);

echo elgg_view_field([
	'#type' => 'payments/method',
	'#label' => elgg_echo('payments:method:select'),
	'name' => 'payment_method',
	'required' => true,
	'transaction' => $transaction,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('paywall:payment:submit'),
	'icon' => 'lock',
]);

elgg_set_form_footer($footer);