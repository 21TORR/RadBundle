<?php declare(strict_types=1);

namespace Torr\Rad\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Base trait that adds time created + modified for entities
 */
trait TimestampsTrait
{
	/**
	 * @ORM\Column(name="time_created", type="datetimetz_immutable")
	 */
	private \DateTimeImmutable $timeCreated;

	/**
	 * @ORM\Column(name="time_modified", type="datetimetz_immutable", nullable=true)
	 */
	private ?\DateTimeImmutable $timeModified = null;

	/**
	 */
	public function getTimeCreated () : \DateTimeImmutable
	{
		return $this->timeCreated;
	}

	/**
	 */
	public function getTimeModified () : ?\DateTimeImmutable
	{
		return $this->timeModified;
	}

	/**
	 */
	public function markAsModified () : void
	{
		$this->timeModified = new \DateTimeImmutable();
	}

	/**
	 * Returns the time the entity was last touched (either created or modified).
	 */
	public function getLastModificationTime () : \DateTimeImmutable
	{
		return $this->getTimeModified() ?? $this->getTimeCreated();
	}
}
