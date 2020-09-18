<?php declare(strict_types=1);

namespace Torr\Rad;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Torr\Rad\DependencyInjection\TorrRadBundleExtension;

class TorrRadBundle extends Bundle
{
	/**
	 * @inheritDoc
	 */
	public function getContainerExtension () : ExtensionInterface
	{
		return new TorrRadBundleExtension($this);
	}

	/**
	 * @inheritDoc
	 */
	public function getPath () : string
	{
		return \dirname(__DIR__);
	}
}
