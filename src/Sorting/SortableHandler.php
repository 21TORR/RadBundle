<?php declare(strict_types=1);

namespace Torr\Rad\Sorting;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Torr\Rad\Entity\Interfaces\SortableEntityInterface;

/**
 * Sortable handler, that helps with sorting entities.
 * It provides methods that implement the most common sorting interactions.
 *
 * The $where array in the methods are relevant when fetching the context of the entities:
 * the methods will act for all entities of the same type with the same $where values.
 */
final class SortableHandler
{
	private EntityRepository $repository;


	/**
	 */
	public function __construct (EntityRepository $repository)
	{
		$this->repository = $repository;
	}


	/**
	 * Returns the next sort order value.
	 */
	public function getNextSortOrder (array $where = []) : int
	{
		$current = $this->createQueryBuilder($where)
			->select("MAX(entity.sortOrder)")
			->getQuery()
			->getSingleScalarResult();

		return null !== $current
			? 1 + (int) $current
			: 0;
	}


	/**
	 * Changes the sort order, so that the $entity is before the $before entity.
	 * If $before is null, the $entity will be added at the end.
	 *
	 * @return bool whether the sort operation was successful
	 */
	public function sortBefore (
		SortableEntityInterface $entity,
		?SortableEntityInterface $before,
		array $where = []
	) : bool
	{
		if ($entity === $before)
		{
			return false;
		}

		$entities = $this->createQueryBuilder($where)
			->select("entity")
			->addOrderBy("entity.sortOrder", "asc")
			->getQuery()
			->iterate();

		$sortOrder = 0;

		foreach ($entities as $row)
		{
			/** @var SortableEntityInterface $existing */
			$existing = $row[0];

			// the entity itself should be skipped (if it already exists),
			// as both cases will be handled below.
			if ($existing === $entity)
			{
				continue;
			}

			// The $before entity was found, so insert $entity before it.
			if ($existing === $before)
			{
				$entity->setSortOrder($sortOrder);
				++$sortOrder;
			}

			$existing->setSortOrder($sortOrder);
			++$sortOrder;
		}

		// if $before is null, the $entity was not added above and will be added here now
		if (null === $before)
		{
			$entity->setSortOrder($sortOrder);
		}

		return true;
	}


	/**
	 * Fixes the sort order of all element with the given $where.
	 * It will excluded all given elements.
	 *
	 * So eg. with this method, you can fix the sort order before removing an entity.
	 */
	public function fixSortOrder (array $excludedEntities, array $where = []) : void
	{
		$entities = $this->createQueryBuilder($where)
			->select("entity")
			->addOrderBy("entity.sortOrder", "asc")
			->getQuery()
			->iterate();

		$excludedIds = [];

		foreach ($excludedEntities as $entity)
		{
			$excludedIds[(string) $entity->getId()] = true;
		}

		$sortOrder = 0;

		foreach ($entities as $row)
		{
			/** @var SortableEntityInterface $entity */
			$entity = $row[0];

			if (\array_key_exists((string) $entity->getId(), $excludedIds))
			{
				continue;
			}

			$entity->setSortOrder($sortOrder);
			++$sortOrder;
		}
	}


	/**
	 * Creates a query build incorporating the $where clause
	 */
	private function createQueryBuilder (array $where) : QueryBuilder
	{
		$queryBuilder = $this->repository->createQueryBuilder("entity");

		// apply the where clauses
		if (!empty($where))
		{
			$constraint = $queryBuilder->expr()->andX();
			$index = 0;

			foreach ($where as $parameter => $value)
			{
				if (null === $value)
				{
					$constraint->add("entity.{$parameter} IS NULL");
				}
				else
				{
					$constraint->add("entity.{$parameter} = :_where_{$index}");
					$queryBuilder->setParameter("_where_{$index}", $value);
				}

				++$index;
			}

			$queryBuilder->andWhere($constraint);
		}

		return $queryBuilder;
	}
}
