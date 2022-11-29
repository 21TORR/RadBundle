<?php declare(strict_types=1);

namespace Torr\Rad\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Torr\Rad\Api\ApiResponse;

#[AsEventListener(KernelEvents::VIEW, "onView")]
final class ControllerResponseListener
{
	/**
	 */
	public function onView (ViewEvent $event) : void
	{
		$result = $event->getControllerResult();

		if ($result instanceof ApiResponse)
		{
			$event->setResponse(
				new JsonResponse(
					$result->toArray(),
					$result->statusCode,
				),
			);
		}
	}
}
