<?php

namespace hypeJunction\Paywall;

use Elgg\EntityNotFoundException;
use Elgg\Request;
use hypeJunction\Payments\Amount;

class DownloadController {

	public function __invoke(Request $request) {

		$file = $request->getEntityParam();

		if (!$file instanceof \ElggFile) {
			throw new EntityNotFoundException();
		}

		$get_paywall_entity = function (\ElggEntity $entity) use (&$get_paywall_entity) {
			$plans = (array) $entity->paid_download_plans;
			$amount = new Amount((int) $entity->paid_download_amount, $entity->paid_download_currency);

			if (!empty($plans) || !empty($amount->getAmount())) {
				return $entity;
			}

			$owner = $entity->getOwnerEntity();
			if ($owner && $get_paywall_entity($owner)) {
				return $owner;
			}

			$container = $entity->getContainerEntity();
			if ($container && $get_paywall_entity($container)) {
				return $container;
			}
		};

		$allow_download = function(\ElggFile $file) {
			$file->setVolatileData('allow_download', true);

			$user = elgg_get_logged_in_user_entity();

			$log = serialize([
				'user_guid' => $user->guid,
				'ip_address' => _elgg_services()->request->getClientIp(),
			]);

			$file->annotate('log:download', $log, ACCESS_PUBLIC, $user->guid);

			elgg_trigger_event('download', 'file', $file);

			return elgg_redirect_response($file->getDownloadURL());
		};
		
		$entity = $get_paywall_entity($file);

		if (!$entity) {
			return $allow_download($file);
		}

		$plans = (array) $entity->paid_download_plans;
		$amount = new Amount((int) $entity->paid_download_amount, $entity->paid_download_currency);

		if (empty($plans) && empty($amount->getAmount())) {
			return $allow_download($file);
		}

		$owner = $entity->getOwnerEntity();
		if ($owner->canEdit()) {
			return $allow_download($file);
		}

		$svc = elgg()->subscriptions;
		/* @var $svc \hypeJunction\Subscriptions\SubscriptionsService */

		if ($plans) {
			$has_plan = $svc->getSubscriptions(null, $plans, ['count' => true]);

			if ($has_plan) {
				return $allow_download($file);
			}
		}

		$user = elgg_get_logged_in_user_entity();
		if (check_entity_relationship($user->guid, 'paid_download', $entity->guid)) {
			return $allow_download($file);
		}

		$exception = new PostAccessException();
		$exception->setParams([
			'entity' => $entity,
		]);
		$exception->setRedirectUrl(elgg_generate_url('paywall:pay:download', [
			'guid' => $entity->guid,
		]));

		throw $exception;

	
	}
}