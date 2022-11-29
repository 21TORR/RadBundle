<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Api;

use Torr\Rad\Api\ApiResponse;
use PHPUnit\Framework\TestCase;

class ApiResponseTest extends TestCase
{
	/**
	 *
	 */
	public function testMinimal () : void
	{
		$apiResponse = new ApiResponse(false);

		self::assertEquals([
			"ok" => false,
		], $apiResponse->toArray());
	}

	/**
	 *
	 */
	public function testMaximal () : void
	{
		$apiResponse = new ApiResponse(
			true,
			["o" => "hai"],
			"error message",
		);

		self::assertEquals([
			"ok" => true,
			"data" => ["o" => "hai"],
			"error" => "error message",
		], $apiResponse->toArray());
	}

	/**
	 *
	 */
	public function provideStatusCode () : iterable
	{
		yield [200, new ApiResponse(true)];
		yield [400, new ApiResponse(false)];
		yield [418, new ApiResponse(false, statusCode: 418)];
	}

	/**
	 * @dataProvider provideStatusCode
	 */
	public function testStatusCode (int $expected, ApiResponse $apiResponse) : void
	{
		self::assertSame($expected, $apiResponse->toResponse()->getStatusCode());
	}
}
