<?php declare(strict_types=1);

namespace Torr\Rad\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineChangeChecker
{
	/**
	 */
	public function __construct (
		private EntityManagerInterface $entityManager,
	) {}


	/**
	 * Determines whether any content in the entities (or the entities themselves)
	 * has changed. Only changing "timeModified" doesn't count as content change.
	 *
	 * @api
	 */
	public function hasContentChanged () : bool
	{
		$unitOfWork = $this->entityManager->getUnitOfWork();
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
}
