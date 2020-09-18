<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Route;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Tests\Torr\Rad\Fixtures\ExampleEntity;
use Torr\Rad\Exception\Route\InvalidRoutableException;
use Torr\Rad\Route\Routable;

final class RoutableTest extends TestCase
{
	//region Test `->withParameters()`
	public function provideWithParameters () : iterable
	{
		yield [["a" => 1, "b" => 2], ["a" => 1], ["b" => 2]];
		yield [["a" => 2], ["a" => 1], ["a" => 2]];
		yield [["b" => 2], [], ["b" => 2]];
		yield [["a" => 1], ["a" => 1], []];
		yield [["a" => 123], ["a" => 1], ["a" => new ExampleEntity(123)]];
		yield [["a" => 1], ["a" => new ExampleEntity(123)], ["a" => 1]];
		yield [["a" => 234], ["a" => new ExampleEntity(123)], ["a" => new ExampleEntity(234)]];
	}


	/**
	 * @dataProvider provideWithParameters
	 */
	public function testWithParameters (array $expected, array $initialParameters, array $withParameters) : void
	{
		$routable = new Routable("route", $initialParameters);
		$newRoutable = $routable->withParameters($withParameters);

		self::assertNotSame($routable, $newRoutable);
		self::assertSame($expected, $newRoutable->getParameters());
	}
	// endregion


	//region Test `->normalizeParameters()`
	public function provideParameterNormalization () : iterable
	{
		yield [["a" => 1], ["a" => 1]];
		yield [["a" => 1, "b" => 2], ["a" => 1, "b" => 2]];
		yield [["a" => 1, "b" => 123], ["a" => 1, "b" => new ExampleEntity(123)]];
	}


	/**
	 * @dataProvider provideParameterNormalization
	 */
	public function testParameterNormalization (array $expected, array $input) : void
	{
		$routable = new Routable("route", $input);
		self::assertSame($expected, $routable->getParameters());
	}
	//endregion


	//region Test `::generateUrl()`
	/**
	 *
	 */
	public function provideGenerateUrlSimple () : iterable
	{
		yield ["test", "test"];
		yield [null, null];
	}

	/**
	 * @dataProvider provideGenerateUrlSimple
	 */
	public function testGenerateUrlSimple (?string $expectedUrl, $value) : void
	{
		$urlGenerator = $this->createMock(UrlGeneratorInterface::class);
		self::assertSame($expectedUrl, Routable::generateUrl($value, $urlGenerator));
	}

	/**
	 *
	 */
	public function testGenerateUrlComplex () : void
	{
		$urlGenerator = $this->createMock(UrlGeneratorInterface::class);
		$route = "test";
		$parameters = ["a" => 1];
		$referenceType = UrlGeneratorInterface::NETWORK_PATH;

		$value = new Routable($route, $parameters, $referenceType);

		$urlGenerator
			->expects(self::exactly(2))
			->method("generate")
			->with($route, $parameters, $referenceType)
			->willReturn("url");

		self::assertSame("url", Routable::generateUrl($value, $urlGenerator));
		self::assertSame("url", $value->generate($urlGenerator));
	}
	//endregion


	//region Test `::isValidValue()` and `::ensureValidValue()`
	/**
	 */
	public function provideValidValues () : iterable
	{
		// null
		yield [true, null, false];
		yield [false, null, true];

		// string
		yield [true, "some url", false];
		yield [true, "some url", true];

		// Routable
		$routable = new Routable("test");
		yield [true, $routable, false];
		yield [true, $routable, true];

		// invalid types
		yield [false, 3, false];
		yield [false, 3, true];
		yield [false, 3.0, false];
		yield [false, 3.0, true];
		yield [false, true, false];
		yield [false, true, true];
	}


	/**
	 * @dataProvider provideValidValues
	 */
	public function testIsValidValue (bool $expectedValid, $value, bool $isRequired) : void
	{
		self::assertSame($expectedValid, Routable::isValidValue($value, $isRequired));
	}


	/**
	 * @dataProvider provideValidValues
	 */
	public function testEnsureValidValue (bool $expectedValid, $value, bool $isRequired) : void
	{
		if (!$expectedValid)
		{
			$this->expectException(InvalidRoutableException::class);
		}

		Routable::ensureValidValue($value, $isRequired);

		if ($expectedValid)
		{
			self::assertTrue(true, "should not throw");
		}
	}
	//endregion
}
