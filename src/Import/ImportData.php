<?php declare(strict_types=1);

namespace Torr\Rad\Import;

use Symfony\Component\HttpFoundation\Exception\UnexpectedValueException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Torr\Rad\Exception\Import\InvalidImportDataException;

/**
 * @final
 *
 * @implements \IteratorAggregate<string, mixed>
 */
readonly class ImportData implements \IteratorAggregate, \Countable
{
	private PropertyAccessor $accessor;

	public function __construct (
		private array $data,
	)
	{
		$this->accessor = PropertyAccess::createPropertyAccessor();
	}

	/**
	 *
	 */
	public function get (string $path) : mixed
	{
		// for simple paths, we automatically wrap it in [...], so that you don't have to write it explicitly.
		// we only require it for nested paths / complex names
		if (preg_match('~^[a-z0-9\\-_]+$~', $path))
		{
			$path = "[{$path}]";
		}

		return $this->accessor->getValue($this->data, $path);
	}

	// region String
	/**
	 *
	 */
	public function getString (string $path) : string
	{
		return $this->filterOutNull(
			$this->getOptionalString($path),
			"string",
			$path,
		);
	}

	/**
	 *
	 */
	public function getOptionalString (string $path) : ?string
	{
		$value = $this->get($path);

		if (null === $value)
		{
			return null;
		}

		if (!\is_scalar($value) && !$value instanceof \Stringable)
		{
			throw new InvalidImportDataException(\sprintf(
				"Expected string at path '%s', but got '%s'",
				$path,
				get_debug_type($value),
			));
		}

		return (string) $value;
	}

	/**
	 * Returns the value as string and will never throw
	 */
	public function getSafeOptionalString (string $path) : ?string
	{
		try
		{
			return $this->getOptionalString($path);
		}
		catch (InvalidImportDataException)
		{
			return null;
		}
	}
	// endregion

	// region Integer
	/**
	 *
	 */
	public function getInt (string $path) : int
	{
		return $this->filterOutNull(
			$this->getOptionalInt($path),
			"int",
			$path,
		);
	}

	/**
	 *
	 */
	public function getOptionalInt (string $path) : ?int
	{
		$value = $this->filter($path, \FILTER_VALIDATE_INT, [
			"flags" => \FILTER_REQUIRE_SCALAR,
		]);

		return null !== $value
			? (int) $value
			: null;
	}
	// endregion

	// region Float
	/**
	 *
	 */
	public function getFloat (string $path) : float
	{
		return $this->filterOutNull(
			$this->getOptionalFloat($path),
			"float",
			$path,
		);
	}

	/**
	 *
	 */
	public function getOptionalFloat (string $path) : ?float
	{
		$value = $this->filter($path, \FILTER_VALIDATE_FLOAT, [
			"flags" => \FILTER_REQUIRE_SCALAR,
		]);

		return null !== $value
			? (float) $value
			: null;
	}
	// endregion

	// region Boolean
	/**
	 *
	 */
	public function getBoolean (string $path) : bool
	{
		return $this->filterOutNull(
			$this->getOptionalBoolean($path),
			"bool",
			$path,
		);
	}

	/**
	 *
	 */
	public function getOptionalBoolean (string $path) : ?bool
	{
		$value = $this->filter($path, \FILTER_VALIDATE_BOOL, [
			"flags" => \FILTER_REQUIRE_SCALAR,
		]);

		return null !== $value
			? (bool) $value
			: null;
	}
	// endregion

	/**
	 * @template EnumClass of \BackedEnum
	 *
	 * @param class-string<EnumClass> $class
	 */
	public function getEnum (string $path, string $class) : ?\BackedEnum
	{
		$value = $this->get($path);

		if (null === $value)
		{
			return null;
		}

		if (!\is_int($value) && !\is_string($value))
		{
			throw new InvalidImportDataException(\sprintf(
				"Could not use value of type '%s' as value for a backed enum of type '%s' at path '%s'",
				get_debug_type($value),
				$class,
				$path,
			));
		}

		try
		{
			return $class::from($value);
		}
		catch (UnexpectedValueException $exception)
		{
			throw new InvalidImportDataException(
				message: \sprintf(
					"Could not parse value '%s' as value for backend enum '%s' at path '%s'",
					$value,
					$class,
					$path,
				),
				previous: $exception,
			);
		}
	}

	/**
	 * @param int   $filter  FILTER_* constant
	 * @param array $options Flags from FILTER_* constants
	 */
	private function filter (
		string $path,
		int $filter = \FILTER_DEFAULT,
		array $options = [],
	) : mixed
	{
		$value = $this->get($path);

		if (null === $value)
		{
			return null;
		}

		$options['flags'] ??= 0;
		$options['flags'] |= \FILTER_NULL_ON_FAILURE;
		$filtered = filter_var($value, $filter, $options);

		if (null === $filtered)
		{
			throw new InvalidImportDataException(\sprintf(
				"Could not properly filter data of type '%s' with filter type '%d' at path '%s'",
				get_debug_type($value),
				$filter,
				$path,
			));
		}

		return $filtered;
	}

	/**
	 * @template DataType
	 *
	 * @param DataType|null $value
	 *
	 * @return DataType
	 */
	private function filterOutNull (mixed $value, string $expectedType, string $path) : mixed
	{
		if (null === $value)
		{
			throw new InvalidImportDataException(\sprintf(
				"Expected %s at path '%s', but got '%s'",
				$expectedType,
				$path,
				get_debug_type($value),
			));
		}

		return $value;
	}

	/**
	 * Returns an iterator for parameters.
	 *
	 * @return \ArrayIterator<string, mixed>
	 */
	public function getIterator() : \ArrayIterator
	{
		return new \ArrayIterator($this->data);
	}

	/**
	 * Returns the number of parameters.
	 */
	public function count() : int
	{
		return \count($this->data);
	}
}
