<?php declare(strict_types=1);

namespace Torr\Rad\Api;

class ApiResponse
{
	public int $statusCode;
	public ?string $error = null;

	/**
	 */
	public function __construct (
		public readonly bool $ok,
		public readonly mixed $data = null,
	)
	{
		$this->statusCode = $ok ? 200 : 400;
	}


	/**
	 * @return $this
	 */
	public function withStatusCode (int $statusCode) : self
	{
		$this->statusCode = $statusCode;
		return $this;
	}


	/**
	 * @return $this
	 */
	public function withError (?string $error) : self
	{
		$this->error = $error;
		return $this;
	}
}
