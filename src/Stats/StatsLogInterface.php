<?php declare(strict_types=1);

namespace Torr\Rad\Stats;

/**
 * Base stats log, to count several stats while doing long running tasks (like imports).
 */
interface StatsLogInterface
{
	/**
	 * Increments the value for the given key.
	 */
	public function increment (string $key, int $amount = 1) : void;


	/**
	 * Adds a DEBUG log message
	 */
	public function debug (string $message) : void;


	/**
	 * Adds a WARNING log message
	 */
	public function warning (string $message) : void;


	/**
	 * Adds a CRITICAL log message
	 */
	public function critical (string $message) : void;


	/**
	 * Creates a section counter with the given prefix.
	 */
	public function createSectionLog (string $prefix) : self;
}
