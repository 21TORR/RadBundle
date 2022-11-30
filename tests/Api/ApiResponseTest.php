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
		$apiResponse = (new ApiResponse(
			true,
			["o" => "hai"],
		))
			->withError("error message");

		self::assertEquals([
			"ok" => true,
			"data" => ["o" => "hai"],
			"error" => "error message",
		], $apiResponse->toArray());
	}
}
