<?php declare(strict_types=1);

namespace Torr\Rad\Route;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface LinkableInterface
{
	/**
	 */
	public function generateUrl (UrlGeneratorInterface $generator) : string;
}
