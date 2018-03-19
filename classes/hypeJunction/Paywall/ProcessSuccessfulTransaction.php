<?php

namespace hypeJunction\Paywall;

use Elgg\Hook;
use hypeJunction\Payments\TransactionInterface;

class ProcessSuccessfulTransaction {

	/**
	 * Process successful payment
	 *
	 * @param Hook $hook Hook
	 */
	public function __invoke(Hook $hook) {

		$transaction = $hook->getEntityParam();

		if (!$transaction instanceof TransactionInterface) {
			return;
		}

		foreach ($transaction->getOrder()->all() as $item) {
			$product = $item->getProduct();

			if ($product instanceof UnlimitedPostAccess) {
				$guid = $product->post_guid;
				$entity = get_entity($guid);
				$customer = $transaction->getCustomer();

				if ($entity && $customer) {
					add_entity_relationship($customer->guid, 'paid_access', $entity->guid);

					$link = elgg_view('output/url', [
						'href' => $entity->getURL(),
						'text' => $entity->getDisplayName(),
					]);

					$summary = elgg_echo('paywall:paid_access:granted:subject', [$link]);
					$subject = strip_tags($summary);

					$message = elgg_echo('paywall:paid_access:granted:message', [
						$entity->getDisplayName(),
						$entity->getURL(),
					]);

					notify_user($customer->guid, null, $subject, $message, [
						'summary' => $summary,
						'url' => $entity->getURL(),
						'object' => $entity,
						'action' => 'paid_access',
					]);
				}
			} else if ($product instanceof UnlimitedDownload) {
				$guid = $product->post_guid;
				$entity = get_entity($guid);
				$customer = $transaction->getCustomer();

				if ($entity && $customer) {
					add_entity_relationship($customer->guid, 'paid_download', $entity->guid);

					$link = elgg_view('output/url', [
						'href' => $entity->getURL(),
						'text' => $entity->getDisplayName(),
					]);

					$summary = elgg_echo('paywall:paid_download:granted:subject', [$link]);
					$subject = strip_tags($summary);

					$message = elgg_echo('paywall:paid_download:granted:message', [
						$entity->getDisplayName(),
						$entity->getURL(),
					]);

					notify_user($customer->guid, null, $subject, $message, [
						'summary' => $summary,
						'url' => $entity->getURL(),
						'object' => $entity,
						'action' => 'paid_download',
					]);
				}
			}
		}

	}
}