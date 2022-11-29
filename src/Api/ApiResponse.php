<?php declare(strict_types=1);

namespace Torr\Rad\Api;

class ApiResponse
{
	public readonly int $statusCode;

	public function __construct (
		private readonly bool $ok,
		private readonly mixed $data = null,
		private readonly ?string $error = null,
		?int $statusCode = null,
	)
	{
		$this->statusCode = $statusCode ?? ($ok ? 200 : 400);
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
