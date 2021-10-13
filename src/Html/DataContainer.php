<?php declare(strict_types=1);

namespace Torr\Rad\Html;

use Symfony\Component\HttpFoundation\Response;

class DataContainer
{
	/**
	 * Renders the HTML of the data container.
	 */
	public function renderToHtml (?array $data, string $class, ?string $id = null) : string
	{
		if (null === $data)
		{
			return "";
		}

		return \sprintf(
			'<script%s class="_data-container %s" type="application/json">%s</script>',
			null !== $id ? ' id="' . \htmlspecialchars($id, \ENT_QUOTES) . '"' : "",
			\htmlspecialchars($class, \ENT_QUOTES),
			\htmlspecialchars(
				\json_encode($data, \JSON_THROW_ON_ERROR),
				\ENT_NOQUOTES,
			),
		);
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
