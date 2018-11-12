<?php

namespace hypeJunction\Paywall;

use Elgg\Hook;

class SetDownloadUrl {

	use Downloadable;

	/**
	 * Rewrite download URL
	 *
	 * @param Hook $hook Hook
	 *
	 * @return bool
	 */
	public function __invoke(Hook $hook) {

		$file = $hook->getEntityParam();

		if (!$file instanceof \ElggFile) {
			return null;
		}

		if ($file->getVolatileData('allow_download')) {
			return null;
		}

		if ($this->isPaywallEnabled($file)) {
			$url = elgg_generate_url('paywall:download', [
				'guid' => $file->guid,
			]);

			return elgg_normalize_site_url($url);
		}
	}
}