<?php declare(strict_types=1);

namespace Torr\Rad\Model;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Torr\Rad\Entity\Interfaces\EntityInterface;

/**
 * Base model that implements some basic integration.
 */
abstract class Model implements ModelInterface
{
	protected EntityManagerInterface $entityManager;

	/**
	 */
	public function __construct (ManagerRegistry $registry)
	{
		$entityManager = $registry->getManager();
		\assert($entityManager instanceof EntityManagerInterface);
		$this->entityManager = $entityManager;
	}


	/**
	 * @inheritDoc
	 */
	public function add (EntityInterface $entity)
	{
		$this->entityManager->persist($entity);
		return $this;
	}


	/**
	 * @inheritDoc
	 */
	public function update (EntityInterface $entity)
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
	public function remove (EntityInterface $entity)
	{
		$this->entityManager->remove($entity);
		return $this;
	}


	/**
	 * @inheritDoc
	 */
	public function flush ()
	{
		$this->entityManager->flush();
		return $this;
	}
}
