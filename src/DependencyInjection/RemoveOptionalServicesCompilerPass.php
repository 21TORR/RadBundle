<?php declare(strict_types=1);

namespace Torr\Rad\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Torr\Rad\Doctrine\DoctrineChangeChecker;
use Torr\Rad\Security\AbilitiesVoter;

final class RemoveOptionalServicesCompilerPass implements CompilerPassInterface
{
	/**
	 * @inheritDoc
	 */
	public function process (ContainerBuilder $container) : void
	{
		if (!\class_exists(RoleHierarchyVoter::class))
		{
			$container->removeDefinition(AbilitiesVoter::class);
		}

		if (!\class_exists(EntityManagerInterface::class))
		{
			$container->removeDefinition(DoctrineChangeChecker::class);
		}
	}
}
