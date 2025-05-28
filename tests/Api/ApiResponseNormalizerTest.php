<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Translator;
use Torr\Rad\Api\ApiResponse;
use Torr\Rad\Api\ApiResponseNormalizer;

/**
 * @internal
 */
final class ApiResponseNormalizerTest extends TestCase
{
	/**
	 *
	 */
	public function testMinimal () : void
	{
		$apiResponse = new ApiResponse(400);
		$normalizer = new ApiResponseNormalizer(new Translator("de"));

		self::assertSame([
			"ok" => false,
		], $normalizer->normalize($apiResponse));
	}

	/**
	 *
	 */
	public function testMaximal () : void
	{
		$apiResponse = (new ApiResponse(
			200,
			["o" => "hai"],
		))
			->withError("error code", "error message");
		$normalizer = new ApiResponseNormalizer(new Translator("de"));

		self::assertSame([
			"ok" => true,
			"data" => ["o" => "hai"],
			"error" => "error code",
			"errorMessage" => "error message",
		], $normalizer->normalize($apiResponse));
	}
}
