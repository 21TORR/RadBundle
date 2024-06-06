<?php declare(strict_types=1);

namespace Torr\Rad\Exception\Structure;

use Torr\Rad\Exception\RadException;

final class MissingArgumentException extends \InvalidArgumentException implements RadException
{
	/**
	 * @param string[] $allKeys
	 */
	public static function create (
		string $missingKey,
		array $allKeys,
		?\Throwable $previous = null,
	) : static
	{
		return new self(
			\sprintf(
				"Missing argument '%s'. Only keys registered are %s",
				$missingKey,
				\implode(", ", $allKeys),
			),
			previous: $previous,
		);
	}
}
