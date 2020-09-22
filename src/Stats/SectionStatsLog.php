<?php declare(strict_types=1);

namespace Torr\Rad\Stats;

/**
 * A stats logger that logs messages in a specific section
 */
final class SectionStatsLog implements StatsLogInterface
{
	private StatsLogInterface $parent;
	private string $prefix;

	/**
	 */
	public function __construct (StatsLogInterface $parent, string $prefix)
	{
		$this->parent = $parent;
		$this->prefix = \sprintf("[%s] ", \rtrim($prefix));
	}


	/**
	 * @inheritDoc
	 */
	public function increment (string $key, int $amount = 1) : void
	{
		$this->parent->increment($key, $amount);
	}


	/**
	 * @inheritDoc
	 */
	public function debug (string $message) : void
	{
		$this->parent->debug($this->prefix . $message);
	}


	/**
	 * @inheritDoc
	 */
	public function warning (string $message) : void
	{
		$this->parent->warning($this->prefix . $message);
	}


	/**
	 * @inheritDoc
	 */
	public function critical (string $message) : void
	{
		$this->parent->critical($this->prefix . $message);
	}


	/**
	 * @inheritDoc
	 */
	public function createSectionLog (string $prefix) : StatsLogInterface
	{
		return new self($this, $prefix);
	}
}
