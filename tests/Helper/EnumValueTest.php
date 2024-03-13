<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Helper;

use Torr\Rad\Helper\EnumValue;
use PHPUnit\Framework\TestCase;

enum TestEnum : string
{
	case A = "a";
}

/**
 */
class EnumValueTest extends TestCase
{
	/**
	 *
	 */
	public function provideTransform () : iterable
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
