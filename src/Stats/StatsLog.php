<?php declare(strict_types=1);

namespace Torr\Rad\Stats;

use Symfony\Component\Console\Style\SymfonyStyle;

final class StatsLog implements StatsLogInterface
{
	/** @var array[] */
	private array $labels = [];

	/** @var int[] */
	private array $counters = [];

	/** @var string[] */
	private array $debug = [];

	/** @var string[] */
	private array $warnings = [];

	/** @var string[] */
	private array $critical = [];


	/**
	 * Instantiates a new import logger.
	 *
	 * @param (array|string)[] $labels
	 */
	public function __construct (array $labels = [])
	{
		foreach ($labels as $key => $label)
		{
			if (\is_string($label))
			{
				$this->setLabel($key, $label);
			}
			elseif (\is_array($label))
			{
				$this->setLabel($key, $label[0] ?? "?", $label[1] ?? null);
			}
		}
	}


	/**
	 * Sets a label for a given key.
	 */
	public function setLabel (string $key, string $label, ?string $description = null) : void
	{
		$this->labels[$key] = [$label, $description];
	}


	/**
	 * @inheritDoc
	 */
	public function increment (string $key, int $amount = 1) : void
	{
		if (!isset($this->counters[$key]))
		{
			$this->counters[$key] = 0;
		}

		$this->counters[$key] += $amount;
	}


	/**
	 * @inheritDoc
	 */
	public function debug (string $message) : void
	{
		$this->debug[] = $message;
	}


	/**
	 * @inheritDoc
	 */
	public function warning (string $message) : void
	{
		$this->warnings[] = $message;
	}


	/**
	 * @inheritDoc
	 */
	public function critical (string $message) : void
	{
		$this->critical[] = $message;
	}


	/**
	 * @inheritDoc
	 */
	public function createSectionLog (string $prefix) : StatsLogInterface
	{
		return new SectionStatsLog($this, $prefix);
	}


	/**
	 * Prepares the SymfonyStyle compatible table output
	 */
	private function prepareTableOutput () : array
	{
		$labels = $this->labels;

		// add remaining labels
		foreach ($this->counters as $key => $count)
		{
			if (!\array_key_exists($key, $labels))
			{
				$displayLabel = \ucwords(\str_replace("_", " ", $key));
				$labels[$key] = [$displayLabel, null];
			}
		}

		// label config is set up, so build the data
		$rows = [];

		foreach ($labels as $key => $headerConfig)
		{
			$rows[] = [
				$headerConfig[0],
				$this->counters[$key] ?? 0,
				$headerConfig[1],
			];
		}

		return $rows;
	}


	/**
	 * Renders the stats (including the log) in the CLI
	 *
	 * @param bool $includeDebug Flag to show or hide the debug log.
	 *                           If `null` it will be shown in verbose (`-v`) mode is use
	 */
	public function render (SymfonyStyle $io, ?bool $includeDebug = null) : void
	{
		if (null === $includeDebug)
		{
			$includeDebug = $io->isVerbose();
		}

		$rows = \array_map(
			static function (array $row)
			{
				$row[0] = "<fg=yellow>{$row[0]}</>";
				return $row;
			},
			$this->prepareTableOutput()
		);

		$io->table(["Label", "×", "Description"], $rows);

		$listing = [];

		foreach ($this->critical as $line)
		{
			$listing[] = "<fg=red>CRITICAL</> {$line}";
		}

		foreach ($this->warnings as $line)
		{
			$listing[] = "<fg=yellow>WARNING</> {$line}";
		}

		if ($includeDebug)
		{
			foreach ($this->debug as $line)
			{
				$listing[] = "<fg=blue>DEBUG</> {$line}";
			}
		}

		if (!empty($listing))
		{
			$io->newLine();
			$io->listing($listing);
		}
	}
}
