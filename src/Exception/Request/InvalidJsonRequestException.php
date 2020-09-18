<?php declare(strict_types=1);

namespace Torr\Rad\Exception\Request;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Torr\Rad\Exception\RadException;

final class InvalidJsonRequestException extends \RuntimeException implements HttpExceptionInterface, RadException
{
	private int $statusCode;

	/**
	 */
	public function __construct (string $message = "", int $statusCode = 400, ?\Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
		$this->statusCode = $statusCode;
	}

	/**
	 * @inheritDoc
	 */
	public function getStatusCode () : int
	{
		return $this->statusCode;
	}

	/**
	 * @inheritDoc
	 */
	public function getHeaders () : array
	{
		return [];
	}
}
