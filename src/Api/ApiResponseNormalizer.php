<?php declare(strict_types=1);

namespace Torr\Rad\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class ApiResponseNormalizer
{
	/**
	 */
	public function __construct (
		private TranslatorInterface $translator,
	) {}

	/**
	 * Normalizes the given API response
	 *
	 * @return array{"ok": bool, "data"?: mixed, "error"?: string}
	 */
	public function normalize (ApiResponse $apiResponse) : array
	{
		return array_filter(
			[
				"ok" => $apiResponse->isOk(),
				"data" => $apiResponse->data,
				"error" => $apiResponse->error,
				"errorMessage" => $apiResponse->errorMessage instanceof TranslatableInterface
					? $apiResponse->errorMessage->trans($this->translator)
					: $apiResponse->errorMessage,
			],
			static fn (mixed $value) => null !== $value,
		);
	}

	/**
	 * Creates a Symfony response from the given ApiResponse
	 */
	public function createResponse (ApiResponse $apiResponse) : JsonResponse
	{
		return new JsonResponse(
			$this->normalize($apiResponse),
			$apiResponse->statusCode,
		);
	}
}
