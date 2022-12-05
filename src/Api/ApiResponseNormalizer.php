<?php declare(strict_types=1);

namespace Torr\Rad\Api;

final class ApiResponseNormalizer
{
	/**
	 * Normalizes the given API response
	 */
	public function normalize (ApiResponse $apiResponse) : array
	{
		return \array_filter(
			[
				"ok" => $apiResponse->ok,
				"data" => $apiResponse->data,
				"error" => $apiResponse->error,
			],
			static fn (mixed $value) => null !== $value,
		);
	}
}
