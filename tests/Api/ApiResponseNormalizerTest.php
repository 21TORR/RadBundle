<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Api;

use PHPUnit\Framework\TestCase;
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
		$normalizer = new ApiResponseNormalizer();

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
			->withError("error message");
		$normalizer = new ApiResponseNormalizer();

		self::assertSame([
			"ok" => true,
			"data" => ["o" => "hai"],
			"error" => "error message",
		], $normalizer->normalize($apiResponse));
	}
}
