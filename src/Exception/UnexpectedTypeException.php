<?php declare(strict_types=1);

namespace Torr\Rad\Exception;

class UnexpectedTypeException extends \InvalidArgumentException implements RadException
{
	/**
	 * @param mixed $value
	 */
	public function __construct($value, string $expectedType, ?\Throwable $previous = null)
	{
		parent::__construct(
			\sprintf('Expected argument of type %s, "%s" given', $expectedType, \get_debug_type($value)),
			0,
			$previous
		);
	}
}
