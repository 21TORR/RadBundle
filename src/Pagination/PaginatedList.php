<?php declare(strict_types=1);

namespace Torr\Rad\Pagination;

/**
 * VO for lists with pagination.
 *
 * @phpstan-template T
 */
final class PaginatedList
{
	/** @phpstan-var iterable<T>  */
	private iterable $list;
	private Pagination $pagination;

	/**
	 */
	public function __construct (iterable $list, Pagination $pagination)
	{
		$this->list = $list;
		$this->pagination = $pagination;
	}


	/**
	 * @phpstan-return iterable<T>
	 */
	public function getList () : iterable
	{
		return $this->list;
	}


	/**
	 */
	public function getPagination () : Pagination
	{
		return $this->pagination;
	}


	/**
	 * Creates a new paginated list that displays the given items on a single page.
	 */
	public static function fromArray (array $list)
	{
		$total = \count($list);
		$perPage = \max(1, $total);
		return new self($list, new Pagination(1, $perPage, $total));
	}
}
