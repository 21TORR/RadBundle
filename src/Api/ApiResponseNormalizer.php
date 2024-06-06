<?php declare(strict_types=1);

namespace Torr\Rad\Api;

final class ApiResponseNormalizer
{
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
			],
			static fn (mixed $value) => null !== $value,
		);
	}
}
