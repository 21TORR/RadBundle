<?php declare(strict_types=1);

namespace Torr\Rad\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Torr\Rad\Entity\Interfaces\SortableEntityInterface;

/**
 * Base trait that adds the data structure to make an entity sortable.
 */
trait SortOrderTrait
{
	/**
	 * @ORM\Column(name="sort_order", type="integer")
	 */
	private ?int $sortOrder = null;


	/**
	 * {@see SortableEntityInterface::getSortOrder()}
	 */
	public function getSortOrder () : ?int
	{
		return $this->sortOrder;
	}


	/**
	 * {@see SortableEntityInterface::setSortOrder()}
	 */
	public function setSortOrder (int $sortOrder) : void
	{
		$this->sortOrder = $sortOrder;
	}
}
