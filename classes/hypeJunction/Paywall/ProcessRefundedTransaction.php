<?php

namespace hypeJunction\Paywall;

use Elgg\Hook;
use hypeJunction\Payments\TransactionInterface;

class ProcessRefundedTransaction {

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

				remove_entity_relationship($transaction->getCustomer()->guid, 'paid_access', $guid);
			} else if ($product instanceof UnlimitedDownload) {
				$guid = $product->post_guid;

				remove_entity_relationship($transaction->getCustomer()->guid, 'paid_download', $guid);
			}
		}

	}
}