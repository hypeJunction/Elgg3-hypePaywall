<?php

namespace hypeJunction\Paywall;

use Elgg\EntityNotFoundException;
use Elgg\Request;
use hypeJunction\Payments\Amount;

class DownloadController {

	use Downloadable;

	/**
	 * @param Request $request
	 *
	 * @return \Elgg\Http\RedirectResponse
	 * @throws EntityNotFoundException
	 * @throws PostAccessException
	 * @throws \DataFormatException
	 */
	public function __invoke(Request $request) {

		$file = $request->getEntityParam();

		if (!$file instanceof \ElggFile) {
			throw new EntityNotFoundException();
		}

		if (!$file->canDownload()) {
			$exception = new PostAccessException();
			$exception->setParams([
				'entity' => $file,
			]);
			$exception->setRedirectUrl($this->getPaymentUrl($file));

			throw $exception;
		}

		return $this->download($file);
	}
}