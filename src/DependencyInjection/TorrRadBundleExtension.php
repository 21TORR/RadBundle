<?php declare(strict_types=1);

namespace Torr\Rad\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Torr\BundleHelpers\Bundle\BundleExtension;
use Torr\Rad\Doctrine\Types\SerializedType;

class TorrRadBundleExtension extends BundleExtension implements PrependExtensionInterface
{
	/**
	 * @inheritDoc
	 */
	public function prepend (ContainerBuilder $container) : void
	{
		if ($container->hasExtension("doctrine"))
		{
			$container->prependExtensionConfig("doctrine", [
				"dbal" => [
					"types" => [
						SerializedType::NAME => SerializedType::class,
					],
				],
			]);
		}
	}
}
