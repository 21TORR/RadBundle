<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Structure;

use PHPUnit\Framework\TestCase;
use Tests\Torr\Rad\Fixtures\ExampleBackedEnum;
use Tests\Torr\Rad\Fixtures\ExampleEntity;
use Tests\Torr\Rad\Fixtures\ExampleEnum;
use Torr\Rad\Exception\Structure\ImmutableDataException;
use Torr\Rad\Exception\Structure\InvalidArgumentTypeException;
use Torr\Rad\Exception\Structure\MissingArgumentException;
use Torr\Rad\Structure\ArgumentBag;

/**
 * @internal
 */
final class ArgumentBagTest extends TestCase
{
	/**
	 *
	 */
	public function testValidGetters () : void
	{
		$bag = self::createBag();

		// check specialized getters
		self::assertSame(ExampleEnum::Test, $bag->getObject("enum", ExampleEnum::class));
		self::assertSame(ExampleBackedEnum::Test, $bag->getObject("backed-enum", ExampleBackedEnum::class));
		self::assertSame(11, $bag->getInt("int"));
		self::assertSame(4.2, $bag->getFloat("float"));
		self::assertSame("test", $bag->getString("string"));
		self::assertTrue($bag->getBool("bool"));
		self::assertInstanceOf(ExampleEntity::class, $bag->getObject("object", ExampleEntity::class));
		self::assertNull($bag->get("null"));

		// check generic getters
		self::assertSame(ExampleEnum::Test, $bag->get("enum"));
		self::assertSame(ExampleBackedEnum::Test, $bag->get("backed-enum"));
		self::assertSame(11, $bag->get("int"));
		self::assertSame(4.2, $bag->get("float"));
		self::assertSame("test", $bag->get("string"));
		self::assertTrue($bag->get("bool"));
		self::assertInstanceOf(ExampleEntity::class, $bag->get("object"));
		self::assertNull($bag->get("null"));

		// optional: missing
		self::assertNull($bag->getOptional("missing"));
	}

	/**
	 *
	 */
	public function testMissingGetter () : void
	{
		$this->expectException(MissingArgumentException::class);
		$bag = self::createBag();

		$bag->get("missing");
	}

	/**
	 *
	 */
	public static function provideInvalidGetters () : iterable
	{
		$bag = self::createBag();

		$correctMapping = [
			"enum" => static fn (string $key) => $bag->getObject($key, ExampleEnum::class),
			"backed-enum" => static fn (string $key) => $bag->getObject($key, ExampleBackedEnum::class),
			"int" => $bag->getInt(...),
			"float" => $bag->getFloat(...),
			"string" => $bag->getString(...),
			"bool" => $bag->getBool(...),
			"object" => static fn (string $key) => $bag->getObject($key, ExampleEntity::class),
		];

		foreach ($correctMapping as $key => $getter)
		{
			foreach (array_keys($correctMapping) as $invalidKey)
			{
				if ($invalidKey === $key)
				{
					continue;
				}

				yield "testing required getter for '{$key}' with key '{$invalidKey}'" => [
					$getter,
					$invalidKey,
				];
			}

			yield "testing required getter for '{$key}' with key 'null'" => [
				$getter,
				"null",
			];
		}
	}

	/**
	 * @dataProvider provideInvalidGetters
	 */
	public function testInvalidGetters (callable $getter, string $key) : void
	{
		$this->expectException(InvalidArgumentTypeException::class);
		$getter($key);
	}

	/**
	 *
	 */
	public function testArrayAccess () : void
	{
		$bag = self::createBag();

		self::assertSame($bag["object"], $bag->get("object"));
		self::assertSame(ExampleEnum::Test, $bag["enum"]);
	}

	/**
	 *
	 */
	public function testInvalidArraySet () : void
	{
		$this->expectException(ImmutableDataException::class);
		$bag = self::createBag();

		$bag["nope"] = "nope";
	}

	/**
	 *
	 */
	public function testInvalidArrayUnset () : void
	{
		$this->expectException(ImmutableDataException::class);
		$bag = self::createBag();

		unset($bag["nope"]);
	}

	/**
	 *
	 */
	private static function createBag () : ArgumentBag
	{
		return new ArgumentBag([
			"enum" => ExampleEnum::Test,
			"backed-enum" => ExampleBackedEnum::Test,
			"int" => 11,
			"float" => 4.2,
			"string" => "test",
			"bool" => true,
			"object" => new ExampleEntity(),
			"null" => null,
		]);
	}
}
