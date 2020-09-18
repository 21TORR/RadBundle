<?php declare(strict_types=1);

namespace Torr\Rad\Pagination\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Torr\Rad\Pagination\PaginatedList;
use Torr\Rad\Pagination\Pagination;

/**
 * Model helper class, that helps with pagination doctrine Query Builder queries.
 */
final class Paginator
{
	/**
	 * Fetches the paginated query result content.
	 */
	public function fetchPaginated (QueryBuilder $queryBuilder, Pagination $pagination) : PaginatedList
	{
		$total = \count(new DoctrinePaginator($queryBuilder->getQuery()));
		$adjustedPagination = $pagination->withNumberOfItems($total);
		$list = [];

		if ($total > 0)
		{
			$queryBuilder
				->setFirstResult($adjustedPagination->getDatabaseRowOffset())
				->setMaxResults($pagination->getPerPage());

			$list = \iterator_to_array(new DoctrinePaginator($queryBuilder->getQuery()));
		}

		return new PaginatedList($list, $adjustedPagination);
	}
}
