<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Pagination;

use PHPUnit\Framework\TestCase;
use Torr\Rad\Pagination\Pagination;

final class PaginationTest extends TestCase
{
	//region Test `->getMaxPage()`
	/**
	 */
	public function provideMaxPage () : iterable
	{
		yield [10, 10, 1];
		yield [5, 10, 2];
		yield [5, 9, 2];
		yield [6, 11, 2];
		yield [10, 100, 10];
		yield [1, 0, 10];
		yield [1, 10, 10];
	}


	/**
	 * @dataProvider provideMaxPage
	 */
	public function testMaxPage (int $expectedMaxPage, int $total, int $perPage) : void
	{
		$pagination = new Pagination(1, $perPage, $total);
		self::assertSame($expectedMaxPage, $pagination->getMaxPage());
	}
	//endregion

	//region Test `->isValid()` + `->getCurrent()`
	/**
	 */
	public function provideValid () : iterable
	{
		yield [true, 1, 1, 10, 20];
		yield [true, 2, 2, 10, 20];
		yield [false, 2, 3, 10, 20];
		yield [true, 3, 3, 10, 21];
		yield [false, 1, 0, 10, 21];
		yield [false, 1, -5, 10, 21];
		yield [false, 3, 10, 10, 21];
	}

	/**
	 * @dataProvider provideValid
	 */
	public function testValid (bool $expectedValid, int $expectedCurrent, int $current, int $perPage, int $total) : void
	{
		$pagination = new Pagination($current, $perPage, $total);

		self::assertSame($expectedValid, $pagination->isValid());
		self::assertSame($expectedCurrent, $pagination->getCurrentPage());
	}
	//endregion


	//region Test `->getNextPage()`
	/**
	 */
	public function provideNext () : iterable
	{
		yield [5, 4, 1, 5];
		yield [2, 1, 1, 5];
		yield [null, 5, 1, 5];
		yield [null, 10, 1, 5];
	}

	/**
	 * @dataProvider provideNext
	 */
	public function testNext (?int $expected, int $current, int $perPage, int $total)
	{
		$pagination = new Pagination($current, $perPage, $total);
		self::assertSame($expected, $pagination->getNextPage());
	}
	//endregion


	//region Test Invalid Construction
	/**
	 */
	public function provideInvalidConstruction () : iterable
	{
		yield "total < 0" => [1, -1];
		yield "per page < 1 (= 0)" => [0, 1];
		yield "per page < 1 (= -1)" => [-1, 1];
	}


	/**
	 * @dataProvider provideInvalidConstruction
	 */
	public function testInvalidConstruction (int $perPage, int $total) : void
	{
		$this->expectException(\InvalidArgumentException::class);
		new Pagination(1, $perPage, $total);
	}
	//endregion


	//region Test `->withNumberOfItems()`
	/**
	 *
	 */
	public function testWithNumberOfItems () : void
	{
		$pagination = new Pagination(2, 5, 11);
		self::assertSame(2, $pagination->getCurrentPage());
		self::assertSame(3, $pagination->getMaxPage());
		self::assertSame(11, $pagination->getNumberOfItems());
		self::assertTrue($pagination->isValid());

		$newPagination = $pagination->withNumberOfItems(3);
		self::assertNotSame($pagination, $newPagination);
		self::assertSame(1, $newPagination->getCurrentPage());
		self::assertSame(1, $newPagination->getMaxPage());
		self::assertFalse($newPagination->isValid());
	}


	/**
	 * Tests that even an invalid current page is preserved, for when the total number of items change.
	 */
	public function testPreserveCurrentPage () : void
	{
		// invalid current page, is normalized
		$pagination = new Pagination(10, 2, 5);
		self::assertSame(3, $pagination->getCurrentPage());

		// the current page is now valid, not normalized anymore
		$newPagination = $pagination->withNumberOfItems(100);
		self::assertSame(10, $newPagination->getCurrentPage());
	}
	//endregion


	//region Test `->toArray()`
	/**
	 *
	 */
	public function testToArray () : void
	{
		$pagination = new Pagination(2, 5, 11);

		self::assertEquals([
			"current" => 2,
			"min" => 1,
			"max" => 3,
			"next" => 3,
			"prev" => 1,
			"perPage" => 5,
			"total" => 11,
			"valid" => true,
		], $pagination->toArray());
	}
	//endregion


	//region Test `->getDatabaseRowOffset()`
	/**
	 */
	public function provideDatabaseRowOffset () : iterable
	{
		yield [0, 0, 5, 0];
		yield [0, 0, 5, 20];
		yield [0, 1, 5, 20];
		yield [5, 2, 5, 20];
	}

	/**
	 * @dataProvider provideDatabaseRowOffset
	 */
	public function testDatabaseRowOffset (int $expected, int $current, int $perPage, int $total) : void
	{
		$pagination = new Pagination($current, $perPage, $total);

		self::assertSame($expected, $pagination->getDatabaseRowOffset());
	}
	//endregion
}
