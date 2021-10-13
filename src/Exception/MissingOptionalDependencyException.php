<?php declare(strict_types=1);

namespace Torr\Rad\Exception;

final class MissingOptionalDependencyException extends \Exception implements RadException
{
	/**
	 * @inheritDoc
	 */
	public function __construct (string $package, ?\Throwable $previous = null)
	{
		parent::__construct(
			\sprintf("Missing optional dependency: %s", $package),
			0,
			$previous,
		);
	}

}
