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
	public function update (EntityInterface $entity) : static
	{
		// automatic integration for entities that use the TimestampsTrait
		if (\method_exists($entity, 'markAsModified'))
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
	 * @inheritDoc
	 */
	public function flush () : static
	{
		$this->entityManager->flush();
		return $this;
	}
}
