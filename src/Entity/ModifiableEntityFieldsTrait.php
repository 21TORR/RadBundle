<?php declare(strict_types=1);

namespace Torr\Rad\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use function Symfony\Component\Clock\now;

/**
 * Base trait for any entity that is modifiable
 *
 * Implement {@see EntityInterface} in your entity as well.
 */
trait ModifiableEntityFieldsTrait
{
	use EntityFieldsTrait;

	/**
	 *
	 */
	#[ORM\Column(name: "time_modified", type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
	private ?\DateTimeImmutable $timeModified = null;


	/**
	 *
	 */
	public function getTimeModified () : ?\DateTimeImmutable
	{
		return $this->timeModified;
	}

	/**
	 *
	 */
	public function markAsModified () : void
	{
		$this->timeModified = now();
	}

	/**
	 * Returns the time the entity was last touched (either created or modified).
	 */
	public function getLastModificationTime () : \DateTimeImmutable
	{
		return $this->getTimeModified() ?? $this->getTimeCreated();
	}
}
