<?php declare(strict_types=1);

namespace Torr\Rad\Route;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Torr\Rad\Entity\Interfaces\EntityInterface;

/**
 * A route configuration VO, that defers the actual generation of the route to a later point.
 */
final class Linkable implements LinkableInterface
{
	public const REQUIRED = true;
	public const OPTIONAL = false;

	private array $parameters;

	/**
	 * The parameters match the ones from {@see UrlGeneratorInterface::generate()}
	 */
	public function __construct (
		private readonly string $route,
		array $parameters = [],
		private readonly int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH,
	)
	{
		$this->parameters = $this->normalizeParameters($parameters);
	}


	/**
	 */
	public function getRoute () : string
	{
		return $this->route;
	}


	/**
	 */
	public function getParameters () : array
	{
		return $this->parameters;
	}


	/**
	 */
	public function getReferenceType () : int
	{
		return $this->referenceType;
	}


	/**
	 * Normalizes the parameters.
	 *
	 * Parameters support some special forms, currently only objects that implement
	 * {@see EntityInterface} are supported as special case.
	 */
	private function normalizeParameters (array $parameters) : array
	{
		$normalized = [];

		foreach ($parameters as $key => $value)
		{
			$normalized[$key] = $value instanceof EntityInterface
				? $value->getId()
				: $value;
		}

		return $normalized;
	}


	/**
	 * Generates the URL.
	 *
	 * Similar to {@see UrlGeneratorInterface::generate()}.
	 *
	 * @throws RouteNotFoundException
	 * @throws MissingMandatoryParametersException
	 * @throws InvalidParameterException
	 */
	public function generateUrl (UrlGeneratorInterface $generator) : string
	{
		return $generator->generate(
			$this->route,
			$this->parameters,
			$this->referenceType,
		);
	}


	/**
	 * Clones the route and merges the given parameters.
	 */
	public function withParameters (array $additionalParameters) : self
	{
		$clone = clone $this;
		$clone->parameters = \array_replace($clone->parameters, $this->normalizeParameters($additionalParameters));
		return $clone;
	}


	/**
	 * Generates the url for any given value
	 */
	public static function generateUrlFromValue (
		string|LinkableInterface|null $value,
		UrlGeneratorInterface $generator,
	) : ?string
	{
		if (null === $value || \is_string($value))
		{
			return $value;
		}

		return $value->generateUrl($generator);
	}
}
