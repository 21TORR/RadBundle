<?php declare(strict_types=1);

namespace Torr\Rad\Security;

use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

final class AbilitiesVoter extends RoleHierarchyVoter
{
	/**
	 */
	public function __construct (RoleHierarchyInterface $roleHierarchy)
	{
		parent::__construct($roleHierarchy, "CAN_");
	}
}
