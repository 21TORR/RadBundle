<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Search\Tokenizer;

use PHPUnit\Framework\TestCase;
use Torr\Rad\Search\Tokenizer\SimpleTokenizer;

final class SimpleTokenizerTest extends TestCase
{
	/**
	 */
	public function provideTokenize () : iterable
	{
		yield "null" => [null, ""];
		yield "empty" => ["", ""];
		yield "simple" => ["query", "query%"];
		yield "trim" => ["  query  ", "query%"];
		yield "multiple words" => ["query 123 abc", "query% 123% abc%"];
		yield "collapse spaces" => ["  1   2    3  ", "1% 2% 3%"];
		yield "prevent wildcard injection: beginning" => ["%query", "\\%query%"];
		yield "prevent wildcard injection: middle" => ["query%query", "query\\%query%"];
		yield "prevent wildcard injection: end" => ["query%", "query\\%%"];
		yield "full: simple" => ["query", "%query%", SimpleTokenizer::MODE_FULL];
		yield "full: trim" => ["  query  ", "%query%", SimpleTokenizer::MODE_FULL];
		yield "full: multiple words" => ["query 123 abc", "%query% %123% %abc%", SimpleTokenizer::MODE_FULL];
		yield "full: collapse spaces" => ["  1   2    3  ", "%1% %2% %3%", SimpleTokenizer::MODE_FULL];
	}


	/**
	 * @dataProvider provideTokenize
	 */
	public function testTokenize (?string $input, string $expected, bool $mode = SimpleTokenizer::MODE_PREFIX) : void
	{
		$tokenizer = new SimpleTokenizer();
		self::assertSame($expected, $tokenizer->transformInput($input, $mode));
	}
}
