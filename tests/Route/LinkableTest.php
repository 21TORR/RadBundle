<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Route;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Tests\Torr\Rad\Fixtures\ExampleEntity;
use Torr\Rad\Route\Linkable;

final class LinkableTest extends TestCase
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
		$linkable = new Linkable("route", $initialParameters);
		$newLinkable = $linkable->withParameters($withParameters);

		self::assertNotSame($linkable, $newLinkable);
		self::assertSame($expected, $newLinkable->getParameters());
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
		$linkable = new Linkable("route", $input);
		self::assertSame($expected, $linkable->getParameters());
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
		self::assertSame($expectedUrl, Linkable::generateUrlFromValue($value, $urlGenerator));
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

		$value = new Linkable($route, $parameters, $referenceType);

		$urlGenerator
			->expects(self::exactly(2))
			->method("generate")
			->with($route, $parameters, $referenceType)
			->willReturn("url");

		self::assertSame("url", Linkable::generateUrlFromValue($value, $urlGenerator));
		self::assertSame("url", $value->generateUrl($urlGenerator));
	}
	//endregion
}
