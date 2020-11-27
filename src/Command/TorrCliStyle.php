<?php declare(strict_types=1);

namespace Torr\Rad\Command;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Style\SymfonyStyle;

final class TorrCliStyle extends SymfonyStyle
{
	private const HIGHLIGHT = "red";

	/**
	 * @inheritDoc
	 */
	public function title (string $message) : void
	{
		$length = Helper::strlenWithoutDecoration($this->getFormatter(), $message) + 4;

		$this->newLine();
		$this->writeln(\sprintf(' <fg=%s>╭%s╮</>', self::HIGHLIGHT, \str_repeat("─", $length)));
		$this->writeln(\sprintf(' <fg=%s>│</>  %s  <fg=red>│</>', self::HIGHLIGHT, $message));
		$this->writeln(\sprintf(' <fg=%s>╰%s╯</>', self::HIGHLIGHT, \str_repeat("─", $length)));
		$this->newLine();
	}


	/**
	 * @inheritDoc
	 */
	public function section (string $message) : void
	{
		$length = Helper::strlenWithoutDecoration($this->getFormatter(), $message);

		$this->newLine();
		$this->writeln([
			$message,
			\sprintf('<fg=%s>%s</>', self::HIGHLIGHT, \str_repeat("─", $length)),
		]);
		$this->newLine();
	}


	/**
	 * @inheritDoc
	 */
	public function table (array $headers, array $rows) : void
	{
		$style = (new TableStyle())
			->setHorizontalBorderChars('─')
			->setVerticalBorderChars('│')
			->setCrossingChars('┼', '╭', '┬', '╮', '┤', '╯', '┴', '╰', '├');
		$style->setCellHeaderFormat('<info>%s</>');

		$table = new Table($this);
		$table->setHeaders($headers);
		$table->setRows($rows);
		$table->setStyle($style);

		$table->render();
		$this->newLine();
	}


	/**
	 * @inheritDoc
	 */
	public function listing (array $elements) : void
	{
		$this->newLine();
		$elements = \array_map(
			static fn ($element) => \sprintf('  <fg=%s>●</> %s', self::HIGHLIGHT, $element),
			$elements
		);

		$this->writeln($elements);
		$this->newLine();
	}
}
