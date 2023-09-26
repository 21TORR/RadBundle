<?php declare(strict_types=1);

namespace Torr\Rad;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Torr\BundleHelpers\Bundle\BundleExtension;
use Torr\Rad\DependencyInjection\RemoveOptionalServicesCompilerPass;

class TorrRadBundle extends Bundle
{
	/**
	 * @inheritDoc
	 */
	public function getContainerExtension () : ExtensionInterface
	{
		return new BundleExtension($this);
	}

	/**
	 * @inheritDoc
	 */
	public function build (ContainerBuilder $container) : void
	{
		$container->addCompilerPass(new RemoveOptionalServicesCompilerPass());
	}

	/**
	 * @inheritDoc
	 */
	public function getPath () : string
	{
		return \dirname(__DIR__);
	}
}
