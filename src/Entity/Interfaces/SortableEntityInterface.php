<?php declare(strict_types=1);

namespace Torr\Rad\Entity\Interfaces;

/**
 * Interface for automatic integration in the sortable helpers.
 */
interface SortableEntityInterface extends EntityInterface
{
	/**
	 */
	public function getSortOrder () : ?int;


	/**
	 */
	public function setSortOrder (int $sortOrder) : void;
}
