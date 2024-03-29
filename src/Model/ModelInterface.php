<?php declare(strict_types=1);

namespace Torr\Rad\Model;

use Torr\Rad\Entity\EntityInterface;

/**
 * Interface for all models in the app
 */
interface ModelInterface
{
	/**
	 * Adds the given entity.
	 *
	 * @return $this
	 */
	public function add (EntityInterface $entity) : static;


	/**
	 * Updates the given entity.
	 *
	 * @return $this
	 */
	public function update (EntityInterface $entity) : static;


	/**
	 * Removes the given entity.
	 *
	 * @return $this
	 */
	public function remove (EntityInterface $entity) : static;


	/**
	 * Flushes all changes (globally) to the database.
	 *
	 * @return $this
	 */
	public function flush () : static;
}
