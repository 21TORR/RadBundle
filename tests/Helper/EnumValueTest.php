<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Helper;

use PHPUnit\Framework\TestCase;
use Torr\Rad\Helper\EnumValue;

enum TestEnum : string
{
	case A = "a";
}

/**
 * @internal
 */
final class EnumValueTest extends TestCase
{
	/**
	 *
	 */
	public static function provideTransform () : iterable
	{
		yield [null, null];
		yield ["test", "test"];
		yield ["a", TestEnum::A];
	}

	/**
	 * @dataProvider provideTransform
	 */
	public function testTransform (?string $expected, mixed $value) : void
	{
		self::assertSame($expected, EnumValue::get($value));
	}
}
