<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Import;

use Tests\Torr\Rad\Fixtures\ExampleBackedEnum;
use Torr\Rad\Exception\Import\InvalidImportDataException;
use Torr\Rad\Import\ImportData;
use PHPUnit\Framework\TestCase;

class ImportDataTest extends TestCase
{
	/**
	 *
	 */
	public function testValid () : void
	{
		$data = new ImportData([
			"int" => 2,
			"int-text" => "3",
			"float" => 2.5,
			"float-text" => "3.5",
			"bool" => true,
			"string" => "text",
			"enum" => "test",
			"null" => null,
			"nested" => [
				"a" => 15,
			],
		]);

		// int
		self::assertSame(2, $data->getOptionalInt("int"));
		self::assertSame(2, $data->getInt("int"));
		self::assertSame(3, $data->getOptionalInt("int-text"));
		self::assertSame(3, $data->getInt("int-text"));

		// float
		self::assertSame(2.5, $data->getOptionalFloat("float"));
		self::assertSame(2.5, $data->getFloat("float"));
		self::assertSame(3.5, $data->getOptionalFloat("float-text"));
		self::assertSame(3.5, $data->getFloat("float-text"));

		// string should transform
		self::assertSame("text", $data->getOptionalString("string"));
		self::assertSame("text", $data->getString("string"));
		self::assertSame("2", $data->getString("int"));
		self::assertSame("2.5", $data->getString("float"));
		self::assertSame("1", $data->getString("bool"));

		// enum
		self::assertSame(ExampleBackedEnum::Test, $data->getEnum("enum", ExampleBackedEnum::class));
		self::assertSame(ExampleBackedEnum::Test, $data->getOptionalEnum("enum", ExampleBackedEnum::class));

		// nested
		self::assertSame(15, $data->getInt("[nested][a]"));

		// safe string
		// -> this is an unparseable string, but it will never throw
		self::assertNull($data->getSafeOptionalString("nested"));
		self::assertSame("text", $data->getSafeOptionalString("string"));

		// optional ignores missing / explicit null paths
		self::assertNull($data->getOptionalString("missing"));
		self::assertNull($data->getOptionalInt("missing"));
		self::assertNull($data->getOptionalFloat("missing"));
		self::assertNull($data->getOptionalBoolean("missing"));
		self::assertNull($data->getOptionalEnum("missing", ExampleBackedEnum::class));
		self::assertNull($data->getOptionalString("null"));
		self::assertNull($data->getOptionalInt("null"));
		self::assertNull($data->getOptionalFloat("null"));
		self::assertNull($data->getOptionalBoolean("null"));
		self::assertNull($data->getOptionalEnum("null", ExampleBackedEnum::class));
	}

	public function provideInvalid () : iterable
	{
		// unparseable: required
		yield "unparseable int" => [
			static fn (ImportData $data) => $data->getInt("string"),
			"Expected int at path 'string', but got 'string'",
		];

		yield "unparseable float: invalid string" => [
			static fn (ImportData $data) => $data->getFloat("string"),
			"Expected float at path 'string', but got 'string'",
		];

		yield "unparseable float: bool" => [
			static fn (ImportData $data) => $data->getFloat("bool"),
			"Expected float at path 'bool', but got 'bool'",
		];

		yield "unparseable bool" => [
			static fn (ImportData $data) => $data->getBoolean("string"),
			"Expected bool at path 'string', but got 'string'",
		];

		yield "unparseable string" => [
			static fn (ImportData $data) => $data->getString("nested"),
			"Expected string at path 'nested', but got 'array'",
		];

		// unparseable: optional
		yield "unparseable optional int" => [
			static fn (ImportData $data) => $data->getOptionalInt("string"),
			"Expected int at path 'string', but got 'string'",
		];

		yield "unparseable optional float: invalid string" => [
			static fn (ImportData $data) => $data->getOptionalFloat("string"),
			"Expected float at path 'string', but got 'string'",
		];

		yield "unparseable optional bool" => [
			static fn (ImportData $data) => $data->getOptionalBoolean("string"),
			"Expected bool at path 'string', but got 'string'",
		];

		yield "unparseable optional string" => [
			static fn (ImportData $data) => $data->getOptionalString("nested"),
			"Expected string at path 'nested', but got 'array'",
		];

		// missing fields
		yield "missing string" => [
			static fn (ImportData $data) => $data->getString("missing"),
			"Expected string at path 'missing', but got 'null'",
		];

		yield "missing int" => [
			static fn (ImportData $data) => $data->getInt("missing"),
			"Expected int at path 'missing', but got 'null'",
		];

		yield "missing float" => [
			static fn (ImportData $data) => $data->getFloat("missing"),
			"Expected float at path 'missing', but got 'null'",
		];

		yield "missing bool" => [
			static fn (ImportData $data) => $data->getBoolean("missing"),
			"Expected bool at path 'missing', but got 'null'",
		];

		yield "missing enum" => [
			static fn (ImportData $data) => $data->getEnum("missing", ExampleBackedEnum::class),
			"Could not fetch value of backed enum of type 'Tests\Torr\Rad\Fixtures\ExampleBackedEnum' at path 'missing', as there is no value at this path.",
		];

		yield "explicit null as string" => [
			static fn (ImportData $data) => $data->getString("null"),
			"Expected string at path 'null', but got 'null'",
		];

		yield "explicit null as int" => [
			static fn (ImportData $data) => $data->getInt("null"),
			"Expected int at path 'null', but got 'null'",
		];

		yield "explicit null as float" => [
			static fn (ImportData $data) => $data->getFloat("null"),
			"Expected float at path 'null', but got 'null'",
		];

		yield "explicit null as bool" => [
			static fn (ImportData $data) => $data->getBoolean("null"),
			"Expected bool at path 'null', but got 'null'",
		];

		yield "explicit null as enum" => [
			static fn (ImportData $data) => $data->getEnum("null", ExampleBackedEnum::class),
			"Could not fetch value of backed enum of type 'Tests\Torr\Rad\Fixtures\ExampleBackedEnum' at path 'null', as there is no value at this path.",
		];
	}

	/**
	 * @dataProvider provideInvalid
	 */
	public function testInvalid (
		callable $callback,
		string $expectedExceptionMessage,
	) : void
	{
		$this->expectException(InvalidImportDataException::class);
		$this->expectExceptionMessage($expectedExceptionMessage);

		$data = new ImportData([
			"int" => 2,
			"int-text" => "3",
			"float" => 2.5,
			"float-text" => "3.5",
			"bool" => true,
			"string" => "text",
			"null" => null,
			"nested" => [
				"a" => 15,
			],
		]);

		$callback($data);
	}
}
