<?php

namespace hypeJunction\Paywall;

use hypeJunction\Payments\Product;

/**
 * @property int $post_guid
 */
class UnlimitedDownload extends Product {

	/**
	 * {@inheritdoc}
	 */
	public function unserialize($serialized) {
		parent::unserialize($serialized);

		if (empty($this->post_guid)) {
			$data = unserialize($serialized);
			$this->post_guid = $data['_post_guid'];
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray() {
		$export = parent::toArray();
		$export['_post_guid'] = $this->post_guid;
		return $export;
	}
}