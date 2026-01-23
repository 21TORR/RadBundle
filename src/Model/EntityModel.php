<?php declare(strict_types=1);

namespace Torr\Rad\Model;

use Doctrine\ORM\EntityManagerInterface;
use Torr\Rad\Entity\EntityInterface;

abstract class EntityModel implements ModelInterface
{
	/**
	 */
	public function __construct (
		protected readonly EntityManagerInterface $entityManager,
	) {}

	/**
	 * @inheritDoc
	 */
	public function add (EntityInterface $entity) : static
	{
		$this->entityManager->persist($entity);

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function update (EntityInterface $entity, bool $markAsModified = true) : static
	{
		// automatic integration for entities that use the TimestampsTrait
		if ($markAsModified && method_exists($entity, 'markAsModified'))
		{
			$entity->markAsModified();
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function remove (EntityInterface $entity) : static
	{
		$this->entityManager->remove($entity);

		return $this;
	}

	/**
	 * If the entity is new, it will "add" it, otherwise it will "update" it
	 */
	public function persist (EntityInterface $entity) : static
	{
		if ($entity->isNew())
		{
			$this->add($entity);
		}
		else
		{
			$this->update($entity);
		}

		return $this;
	}

	/**
	 * Flushes all database changes of all entities to the database
	 */
	public function flush () : static
	{
		$this->entityManager->flush();

		return $this;
	}

	/**
	 * Refreshes/resets the given entity: will reset the entity to the values currently stored in the database
	 */
	public function refresh (EntityInterface $entity) : void
	{
		$this->entityManager->refresh($entity);
	}
}
