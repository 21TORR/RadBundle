<?php declare(strict_types=1);

namespace Torr\Rad;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Torr\Rad\DependencyInjection\TorrRadBundleExtension;

class TorrRadBundle extends Bundle
{
	/**
	 * @inheritDoc
	 */
	public function getContainerExtension ()
	{
		return new TorrRadBundleExtension($this);
	}
}
