<?php declare(strict_types=1);

namespace Torr\Rad\Api;

class ApiResponse
{
	public int $statusCode;
	public ?string $error = null;

	/**
	 */
	public function __construct (
		private readonly bool $ok,
		private readonly mixed $data = null,
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


	/**
	 */
	public function toArray () : array
	{
		return \array_filter(
			[
				"ok" => $this->ok,
				"data" => $this->data,
				"error" => $this->error,
			],
			static fn (mixed $value) => null !== $value,
		);
	}
}
