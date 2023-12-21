<?php declare(strict_types=1);

namespace Torr\Rad\Html;

use Symfony\Component\HttpFoundation\Response;
use Torr\HtmlBuilder\Builder\HtmlBuilder;
use Torr\HtmlBuilder\Node\HtmlElement;

class DataContainer
{
	private HtmlBuilder $htmlBuilder;

	/**
	 *
	 */
	public function __construct ()
	{
		$this->htmlBuilder = new HtmlBuilder();
	}


	/**
	 * Renders the HTML of the data container.
	 */
	public function renderToHtml (?array $data, string $class, ?string $id = null) : string
	{
		if (null === $data)
		{
			return "";
		}

		$element = new HtmlElement("script", [
			"id" => $id,
			"class" => "_data-container {$class}",
			"type" => "application/json",
		], [
			\json_encode($data, \JSON_THROW_ON_ERROR),
		]);

		return $this->htmlBuilder->build($element);
	}


	/**
	 * Renders the data container as response.
	 * (Especially useful for embedded controllers.)
	 */
	public function createResponse (?array $data, string $class, ?string $id = null) : Response
	{
		return new Response($this->renderToHtml($data, $class, $id));
	}
}
