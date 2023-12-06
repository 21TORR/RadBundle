<?php declare(strict_types=1);

namespace Torr\Rad\Cache;

use Symfony\Contracts\Service\ResetInterface;

/**
 * A convenient in-memory wrapper
 * It is useful for temporary caches, that should act like more sophisticated in-memory caches.
 */
final class InMemoryCache implements ResetInterface
{
	/** @var array<string, mixed> */
	private array $cachedValues = [];

	/**
	 * Fetches a value from the pool or computes it if not found.
	 * On cache misses, a callback is called that should return the missing value.
	 */
	public function get (string $key, callable $callback) : mixed
	{
		return $this->cachedValues[$key] ??= $callback();
	}

	/**
	 * Removes an item from the pool.
	 */
	public function delete (string $key) : void
	{
		$this->cachedValues[$key] = null;
	}

	/**
	 * Clears the cache pool.
	 */
	public function reset () : void
	{
		$this->cachedValues = [];
	}
}
