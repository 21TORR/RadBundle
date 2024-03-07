<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Api;

use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;
use Torr\Rad\Api\ApiResponse;
use PHPUnit\Framework\TestCase;

class ApiResponseTest extends TestCase
{
	use ExpectDeprecationTrait;

	/**
	 *
	 */
	public function testOk () : void
	{
		self::assertTrue((new ApiResponse(202))->isOk());
		self::assertFalse((new ApiResponse(418))->isOk());
	}


	/**
	 * @group legacy
	 */
	public function testLegacyBoolConstructorTrue () : void
	{
		$this->expectDeprecation("Since 21torr/rad 3.1.0: Passing a bool as first value to ApiResponse is deprecated. Pass the status code instead.");
		new ApiResponse(true);
	}

	/**
	 * @group legacy
	 */
	public function testLegacyBoolConstructorFalse () : void
	{
		$this->expectDeprecation("Since 21torr/rad 3.1.0: Passing a bool as first value to ApiResponse is deprecated. Pass the status code instead.");
		new ApiResponse(false);
	}

	/**
	 * @group legacy
	 */
	public function testLegacyResettingStatusCode () : void
	{
		$this->expectDeprecation("Since 21torr/rad 3.1.0: Calling ApiResponse::withStatusCode() is deprecated, pass the status code in the constructor instead.");

		(new ApiResponse(201))
			->withStatusCode(418);
	}
}
