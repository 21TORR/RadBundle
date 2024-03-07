<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Torr\Rad\Api\ApiResponse;
use Torr\Rad\Api\ApiResponseNormalizer;
use Torr\Rad\Listener\ControllerResponseListener;
use PHPUnit\Framework\TestCase;

class ControllerResponseListenerTest extends TestCase
{
	/**
	 *
	 */
	public function testIntegration () : void
	{
		$event = $this->createEvent(new ApiResponse(200));

		$listener = new ControllerResponseListener(new ApiResponseNormalizer());
		$listener->onView($event);

		self::assertInstanceOf(JsonResponse::class, $event->getResponse());
	}

	/**
	 *
	 */
	public function testIntegrationWithOtherResult () : void
	{
		$event = $this->createEvent(11);

		$listener = new ControllerResponseListener(new ApiResponseNormalizer());
		$listener->onView($event);

		self::assertNull($event->getResponse());
	}


	/**
	 *
	 */
	public function provideStatusCode () : iterable
	{
		yield [200, new ApiResponse(200)];
		yield [400, new ApiResponse(400)];
		yield [418, new ApiResponse(418)];
	}

	/**
	 * @dataProvider provideStatusCode
	 */
	public function testStatusCode (int $expectedStatusCode, ApiResponse $apiResponse) : void
	{
		$event = $this->createEvent($apiResponse);

		$listener = new ControllerResponseListener(new ApiResponseNormalizer());
		$listener->onView($event);

		$response = $event->getResponse();
		self::assertInstanceOf(JsonResponse::class, $response);;
		self::assertSame($expectedStatusCode, $response->getStatusCode());
	}

	/**
	 *
	 */
	private function createEvent (mixed $controllerResult) : ViewEvent
	{
		return new ViewEvent(
			$this->createMock(KernelInterface::class),
			new Request(),
			HttpKernelInterface::MAIN_REQUEST,
			$controllerResult,
		);
	}
}
