<?php declare(strict_types=1);

namespace Torr\Rad\Helper;

abstract class EnumValue
{
	/**
	 * Helper to resolve an enum / string value to a string.
	 */
	public static function strict (\BackedEnum|string $value) : string
	{
		return $value instanceof \BackedEnum
			? (string) $value->value
			: $value;
	}


	/**
	 * Helper to resolve an enum / string / null value to a ?string.
	 */
	public static function get (\BackedEnum|string|null $value) : ?string
	{
		return null !== $value
			? self::strict($value)
			: $value;
	}
}
