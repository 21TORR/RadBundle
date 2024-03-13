<?php declare(strict_types=1);

namespace Torr\Rad\Api;

class ApiResponse
{
	public int $statusCode;
	public ?string $error = null;

	/**
	 */
	public function __construct (
		bool|int $statusCode,
		public readonly mixed $data = null,
	)
	{
		// @todo remove check in v4, make parameter int only and readonly.
		if (\is_int($statusCode))
		{
			$this->statusCode = $statusCode;
		}
		else
		{
			\trigger_deprecation("21torr/rad", "3.1.0", "Passing a bool as first value to ApiResponse is deprecated. Pass the status code instead.");
			$this->statusCode = $statusCode ? 200 : 400;
		}
	}


	/**
	 * @return $this
	 *
	 * @deprecated Calling ApiResponse::withStatusCode() is deprecated, pass the status code in the constructor instead.
	 */
	public function withStatusCode (int $statusCode) : self
	{
		// @todo remove method in v4
		\trigger_deprecation(
			"21torr/rad",
			"3.1.0",
			"Calling ApiResponse::withStatusCode() is deprecated, pass the status code in the constructor instead.",
		);

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
	 *
	 */
	public function isOk () : bool
	{
		return $this->statusCode >= 200 && $this->statusCode <= 299;
	}
}
