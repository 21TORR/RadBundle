<?php declare(strict_types=1);

namespace Torr\Rad\Entity;

interface EntityInterface
{
	/**
	 * Returns the ID of the entity.
	 */
	public function getId () : ?int;


	/**
	 * Returns whether this entity was already persisted and flushed (`false`) or if it is new (`true`).
	 *
	 * @return bool true if new (= not yet flushed), false otherwise
	 */
	public function isNew () : bool;


	/**
	 * Returns the time the entity was created.
	 */
	public function getTimeCreated () : \DateTimeImmutable;
}
