<?php

namespace hypeJunction\Paywall;

class AddFormField {

	/**
	 * Add paywall fields
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Hook $hook) {

		$fields = $hook->getValue();

		$fields['paid_access'] = new PaidAccessField([
			'type' => 'paywall/access',
			'is_profile_field' => false,
			'section' => 'sidebar',
			'priority' => 101,
		]);

		$fields['paid_download'] = new PaidDownloadField([
			'type' => 'paywall/download',
			'is_profile_field' => false,
			'section' => 'sidebar',
			'priority' => 102,
		]);

		return $fields;
	}
}
