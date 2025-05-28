<?php declare(strict_types=1);

namespace Torr\Rad\Api;

use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ApiResponseNormalizer
{
	/**
	 */
	public function __construct (
		private readonly TranslatorInterface $translator,
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
				"errorMessage" => $apiResponse->errorMessage instanceof TranslatableMessage
					? $apiResponse->errorMessage->trans($this->translator)
					: $apiResponse->errorMessage,
			],
			static fn (mixed $value) => null !== $value,
		);
	}
}
