<?php declare(strict_types=1);

namespace Torr\Rad\Twig;

use Torr\Rad\Html\DataContainer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RadTwigExtension extends AbstractExtension
{
	private DataContainer $dataContainer;


	/**
	 */
	public function __construct (DataContainer $dataContainer)
	{
		$this->dataContainer = $dataContainer;
	}


	/**
	 * @inheritDoc
	 */
	public function getFunctions () : array
	{
		return [
			new TwigFunction("data_container", [$this->dataContainer, "renderToHtml"], ["is_safe" => ["html"]]),
		];
	}
}
