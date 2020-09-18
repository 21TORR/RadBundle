<?php declare(strict_types=1);

namespace Torr\Rad\Twig;

use Torr\Rad\Html\DataContainer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
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
	 * Appends the given string value to the $array $key (and creates the key if it didn't exist)
	 */
	public function appendToArrayKey (array $array, string $key, string $value) : array
	{
		$current = $array[$key] ?? "";
		$array[$key] = \trim("{$current} {$value}");
		return $array;
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


	/**
	 * @inheritDoc
	 */
	public function getFilters () : array
	{
		return [
			new TwigFilter("appendToArrayKey", [$this, "appendToArrayKey"]),
		];
	}
}
