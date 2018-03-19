<?php

namespace hypeJunction\Paywall;

use Elgg\BadRequestException;
use Elgg\EntityNotFoundException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use hypeJunction\Payments\Transaction;
use hypeJunction\Payments\TransactionInterface;

class AccessPaymentAction {

	/**
	 * Make payment
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 * @throws BadRequestException
	 * @throws EntityNotFoundException
	 * @throws \Exception
	 */
	public function __invoke(Request $request) {

		$transaction_id = $request->getParam('transaction_id');
		$transaction = Transaction::getFromId($transaction_id);
		if (!$transaction) {
			throw new EntityNotFoundException();
		}

		$method = $request->getParam('payment_method');
		if (!$method) {
			throw new BadRequestException();
		}

		return elgg_call(ELGG_IGNORE_ACCESS, function () use ($request, $transaction, $method) {

			$transaction->save();

			$gateway = elgg()->payments->getGateway($method);

			/* @var $gateway \hypeJunction\Payments\GatewayInterface */

			$response = $gateway->pay($transaction, $request->getParams());

			if (!$response->getForwardURL() || $response->getForwardURL() === REFERRER) {
				sleep(1); // give gateway a chance to send a webhook

				$items = $transaction->getOrder()->all();
				if ($items) {
					$product = $items[0]->getProduct();
					if ($product instanceof \ElggEntity) {
						$response->setForwardURL($product->getURL());
					}
				}
			}

			return $response;
		});

	}
}