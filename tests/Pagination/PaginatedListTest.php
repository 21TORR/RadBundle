<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Pagination;

use PHPUnit\Framework\TestCase;
use Torr\Rad\Pagination\PaginatedList;
use Torr\Rad\Pagination\Pagination;

final class PaginatedListTest extends TestCase
{
	/**
	 *
	 */
	public function testArrayList () : void
	{
		$pagination = new Pagination(1, 10, 5);
		$list = \range(1, 3);

		$paginatedList = new PaginatedList($list, $pagination);

		self::assertSame($list, $paginatedList->getList());
		self::assertSame($pagination, $paginatedList->getPagination());
	}


	/**
	 *
	 */
	public function testIteratorList () : void
	{
		$pagination = new Pagination(1, 10, 5);
		$list = new \ArrayIterator(\range(1, 3));

		$paginatedList = new PaginatedList($list, $pagination);
		self::assertSame($pagination, $paginatedList->getPagination());
		self::assertSame($list, $paginatedList->getList());
	}


	/**
	 *
	 */
	public function testGeneratorList () : void
	{
		$pagination = new Pagination(1, 10, 5);
		$list = static function () : iterable
		{
			yield 1;
			yield 2;
			yield 3;
		};
		$iterable = $list();

		$paginatedList = new PaginatedList($iterable, $pagination);
		self::assertSame($pagination, $paginatedList->getPagination());
		self::assertSame($iterable, $paginatedList->getList());
	}


	/**
	 */
	public function provideFromArray ()
	{
		yield [11, 1, 1, 11, \range(0, 10)];
		yield [0, 1, 1, 1, []];
	}


	/**
	 * @dataProvider provideFromArray
	 */
	public function testCreateFromArray (
		int $expectedListCount,
		int $expectedCurrent,
		int $expectedMaxPage,
		int $expectedPerPage,
		array $list
	) : void
	{
		$list = PaginatedList::fromArray($list);
		self::assertCount($expectedListCount, $list->getList());
		self::assertSame($expectedCurrent, $list->getPagination()->getCurrentPage());
		self::assertSame($expectedMaxPage, $list->getPagination()->getMaxPage());
		self::assertSame($expectedPerPage, $list->getPagination()->getPerPage());
	}
}
