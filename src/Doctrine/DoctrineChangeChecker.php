<?php declare(strict_types=1);

namespace Torr\Rad\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;
use Torr\Rad\Exception\Doctrine\InvalidDoctrineChangeCheckException;

final readonly class DoctrineChangeChecker
{
	/**
	 */
	public function __construct (
		private ManagerRegistry $managerRegistry,
	) {}


	/**
	 * Determines whether any content globally in any of the entities (or the entities themselves)
	 * has changed. Only changing "timeModified" doesn't count as content change.
	 *
	 * @api
	 */
	public function hasContentChanged () : bool
	{
		$defaultEntityManager = $this->managerRegistry->getManager();

		if (!$defaultEntityManager instanceof EntityManagerInterface)
		{
			throw new InvalidDoctrineChangeCheckException(\sprintf(
				"Default manager is no entity manager, but '%s'",
				\get_debug_type($defaultEntityManager),
			));
		}

		// clone the uow, to not touch the original calculated changesets
		$unitOfWork = clone $defaultEntityManager->getUnitOfWork();
		$unitOfWork->computeChangeSets();

		// If any entity was added / removed, something changed, so early exit
		if (
			\count($unitOfWork->getScheduledEntityInsertions()) > 0
			|| \count($unitOfWork->getScheduledEntityDeletions()) > 0
			|| \count($unitOfWork->getScheduledCollectionUpdates()) > 0
			|| \count($unitOfWork->getScheduledCollectionDeletions()) > 0
		)
		{
			return true;
		}

		foreach ($unitOfWork->getScheduledEntityUpdates() as $entity)
		{
			$changes = $unitOfWork->getEntityChangeSet($entity);

			// if only timeModified changed, then nothing in the content changed
			if (1 === \count($changes) && "timeModified" === \array_key_first($changes))
			{
				continue;
			}

			return true;
		}

		return false;
	}


	/**
	 * Returns the changes of the given entity
	 *
	 * @return array<string, array{mixed, mixed}|PersistentCollection>
	 */
	public function getEntityChanges (object $entity) : array
	{
		$entityClass = \get_class($entity);
		$entityManager = $this->managerRegistry->getManagerForClass($entityClass);

		if (!$entityManager instanceof EntityManagerInterface)
		{
			throw new InvalidDoctrineChangeCheckException(\sprintf(
				"Could not fetch entity manager for entity of type '%s'",
				$entityClass,
			));
		}

		// clone the uow, to not touch the original calculated changesets
		$unitOfWork = clone $entityManager->getUnitOfWork();
		$unitOfWork->computeChangeSets();

		return $unitOfWork->getEntityChangeSet($entity);
	}
}
