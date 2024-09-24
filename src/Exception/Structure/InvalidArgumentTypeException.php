<?php declare(strict_types=1);

namespace Torr\Rad\Exception\Structure;

use Torr\Rad\Exception\RadException;

final class InvalidArgumentTypeException extends \TypeError implements RadException
{
	/**
	 */
	public static function create (
		string $key,
		mixed $value,
		string $expected,
		?\Throwable $previous = null,
	) : static
	{
		return new self(
			\sprintf(
				"Invalid argument type for key '%s': expected %s, but was %s",
				$key,
				$expected,
				get_debug_type($value),
			),
			previous: $previous,
		);
	}
}
