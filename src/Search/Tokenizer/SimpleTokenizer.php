<?php declare(strict_types=1);

namespace Torr\Rad\Search\Tokenizer;

/**
 * Tokenizes the input into search tokens
 */
final class SimpleTokenizer
{
	/**
	 * Marks a prefix search, so the input is a prefix of the search token
	 */
	public const MODE_PREFIX = true;

	/**
	 * Marks a full search, so the input can occur anywhere in the search term.
	 */
	public const MODE_FULL = false;

	/**
	 * Transforms the given input for usage in a query
	 */
	public function transformInput (?string $input, bool $mode = self::MODE_PREFIX)
	{
		$input = \trim((string) $input);

		if ("" === $input)
		{
			return "";
		}

		$words = \preg_split('~\\s+~', $input);
		$tokens = [];

		foreach ($words as $word)
		{
			// prevent against wildcard injection
			$word = \addcslashes($word, "%_");

			$tokens[] = self::MODE_PREFIX === $mode
				? "{$word}%"
				: "%{$word}%";
		}

		return \implode(" ", $tokens);
	}
}
