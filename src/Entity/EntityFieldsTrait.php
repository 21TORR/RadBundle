<?php declare(strict_types=1);

namespace Torr\Rad\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Base trait that adds base fields to any entity.
 *
 * If your entity is modifiable, you should use {@see ModifiableEntityFieldsTrait} instead.
 *
 * Implement {@see EntityInterface} in your entity as well.
 */
trait EntityFieldsTrait
{
	/**
	 *
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue(strategy: "AUTO")]
	#[ORM\Column(name: "id", type: Types::INTEGER)]
	private ?int $id = null;


	/**
	 *
	 */
	#[ORM\Column(name: "time_created", type: "datetimetz_immutable")]
	private \DateTimeImmutable $timeCreated;


	/**
	 *
	 */
	public function getId () : ?int
	{
		return $this->id;
	}


	/**
	 *
	 */
	public function isNew () : bool
	{
		return null === $this->id;
	}


	/**
	 *
	 */
	public function getTimeCreated () : \DateTimeImmutable
	{
		return $this->timeCreated;
	}
}
