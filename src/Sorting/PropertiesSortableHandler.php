<?php declare(strict_types=1);

namespace Torr\Rad\Sorting;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Torr\Rad\Entity\Interfaces\SortableEntityInterface;

/**
 * A special implementation of the {@see SortableHandler} that eases the implementation
 * for the special case, that the $where is always fetched as property from the entity itself.
 */
final class PropertiesSortableHandler
{
	private EntityRepository $repository;
	private SortableHandler $sortable;
	/** @var string[] */
	private array $properties;
	private PropertyAccessor $accessor;


	/**
	 * @param string ...$properties The properties to fetch the $where values from
	 */
	public function __construct (EntityRepository $repository, string ...$properties)
	{
		$this->repository = $repository;
		$this->sortable = new SortableHandler($repository);
		$this->properties = $properties;
		$this->accessor = PropertyAccess::createPropertyAccessor();
	}


	/**
	 * Sets the next sort order value in the given entity.
	 */
	public function setNextSortOrder (SortableEntityInterface $entity) : void
	{
		$entity->setSortOrder(
			$this->sortable->getNextSortOrder($this->generateWhere($entity)),
		);
	}


	/**
	 * Analog to {@see SortableHandler::sortBefore()}.
	 */
	public function sortBefore (SortableEntityInterface $entity, ?SortableEntityInterface $before) : bool
	{
		return $this->areEntitiesCompatible($entity, $before)
			? $this->sortable->sortBefore($entity, $before, $this->generateWhere($entity))
			: false;
	}


	/**
	 * Removes the given entity from the sort order and fixes the sort order for the remaining items.
	 */
	public function removeFromSortOrder (SortableEntityInterface $entity) : void
	{
		$this->sortable->fixSortOrder([$entity], $this->generateWhere($entity));
	}


	/**
	 * Checks whether the two given entities are compatible, i.e. they can be sorted with each other.
	 */
	private function areEntitiesCompatible (SortableEntityInterface $left, ?SortableEntityInterface $right) : bool
	{
		// a single entity is always valid
		if (null === $right)
		{
			return true;
		}

		// the same entity is never valid for sorting
		if ($left === $right)
		{
			return false;
		}

		// compare the value of each property
		foreach ($this->properties as $property)
		{
			$leftValue = $this->accessor->getValue($left, $property);
			$rightValue = $this->accessor->getValue($right, $property);

			if ($leftValue !== $rightValue)
			{
				return false;
			}
		}

		return true;
	}


	/**
	 * Generates the $where array, used in the sortable handler
	 */
	private function generateWhere (SortableEntityInterface $entity) : array
	{
		$where = [];

		foreach ($this->properties as $property)
		{
			$where[$property] = $this->accessor->getValue($entity, $property);
		}

		return $where;
	}
}
