<?php declare(strict_types=1);

namespace Torr\Rad\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Torr\Rad\Entity\Interfaces\EntityInterface;

/**
 * Base trait that adds the primary key ID to an entity
 */
trait IdTrait
{
	/**
	 * @ORM\Id()
	 *
	 * @ORM\GeneratedValue(strategy="AUTO")
	 *
	 * @ORM\Column(name="id", type="integer")
	 */
	#[
		ORM\Id(),
		ORM\GeneratedValue(strategy: "AUTO"),
		ORM\Column(name: "id", type: "integer")
	]
	private ?int $id = null;



	/**
	 * {@see EntityInterface::getId()}
	 */
	public function getId () : ?int
	{
		return $this->id;
	}


	/**
	 * {@see EntityInterface::isNew()}
	 */
	public function isNew () : bool
	{
		return null === $this->id;
	}
}
