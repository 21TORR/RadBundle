<?php declare(strict_types=1);

namespace Torr\Rad\Route;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Torr\Rad\Entity\Interfaces\EntityInterface;
use Torr\Rad\Exception\Route\InvalidRoutableException;

/**
 * A route configuration VO, that defers the actual generation of the route to a later point.
 */
final class Routable
{
	public const REQUIRED = true;
	public const OPTIONAL = false;

	private string $route;
	private array $parameters;
	private int $referenceType;

	/**
	 * The parameters match the ones from {@see UrlGeneratorInterface::generate()}
	 */
	public function __construct (
		string $route,
		array $parameters = [],
		int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
	)
	{
		$this->route = $route;
		$this->parameters = $this->normalizeParameters($parameters);
		$this->referenceType = $referenceType;
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
	 * Same as {@see UrlGeneratorInterface::generate()}.
	 *
	 * @throws RouteNotFoundException
	 * @throws MissingMandatoryParametersException
	 * @throws InvalidParameterException
	 */
	public function generate (UrlGeneratorInterface $generator) : string
	{
		return $generator->generate(
			$this->route,
			$this->parameters,
			$this->referenceType
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
	 *
	 * @param string|static|null $value
	 */
	public static function generateUrl ($value, UrlGeneratorInterface $generator) : ?string
	{
		self::ensureValidValue($value, self::OPTIONAL);

		if (null === $value || \is_string($value))
		{
			return $value;
		}

		\assert($value instanceof self);
		return $value->generate($generator);
	}


	/**
	 * Returns whether the given value is valid for URL generation in {@see self::generateUrl()}.
	 */
	public static function isValidValue ($value, bool $isRequired = self::REQUIRED) : bool
	{
		return null !== $value
			? (\is_string($value) || $value instanceof self)
			: !$isRequired;
	}


	/**
	 * Ensures that the given value is a valid routable target, that can be handled in
	 * {@see self::generateUrl()}.
	 *
	 * @throws InvalidRoutableException
	 */
	public static function ensureValidValue ($value, bool $isRequired = self::REQUIRED) : void
	{
		if (!self::isValidValue($value, $isRequired))
		{
			throw new InvalidRoutableException(
				$value,
				$isRequired
					? \sprintf("string or %s", self::class)
					: \sprintf("string, %s or null", self::class)
			);
		}
	}
}
