<?php declare(strict_types=1);

namespace Torr\Rad\Structure;

use Symfony\Component\HttpFoundation\ParameterBag;
use Torr\Rad\Exception\Structure\InvalidArgumentTypeException;
use Torr\Rad\Exception\Structure\MissingArgumentException;

/**
 * Stricter version of {@see ParameterBag} for usage in flexible argument lists.
 */
final readonly class ArgumentsBag implements \IteratorAggregate, \Countable
{
	/**
	 * @param array<string, array|bool|string|int|float|\UnitEnum|object> $arguments
	 */
	public function __construct (
		private array $arguments = [],
	) {}

	/**
	 *
	 */
	public function all () : array
	{
		return $this->arguments;
	}

	/**
	 * Returns the argument keys.
	 */
	public function keys () : array
	{
		return array_keys($this->arguments);
	}

	/**
	 *  Returns a required argument
	 */
	public function get (string $key) : array|bool|string|int|float|object
	{
		if (!\array_key_exists($key, $this->arguments))
		{
			throw MissingArgumentException::create($key, $this->keys());
		}

		return $this->arguments[$key];
	}

	/**
	 * Returns an optional argument
	 */
	public function getOptional (string $key) : array|bool|string|int|float|object|null
	{
		return $this->arguments[$key] ?? null;
	}

	/**
	 * Returns a non-optional string argument.
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't a string
	 */
	public function getString (string $key) : string
	{
		$value = $this->get($key);

		if (!\is_string($value))
		{
			throw InvalidArgumentTypeException::create($key, $value, "string");
		}

		return $value;
	}

	/**
	 * Returns an optional string argument.
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't a string / null
	 */
	public function getOptionalString (string $key) : string|null
	{
		$value = $this->getOptional($key);

		if (null !== $value && !\is_string($value))
		{
			throw InvalidArgumentTypeException::create($key, $value, "string / null");
		}

		return $value;
	}

	/**
	 * Returns a non-optional int argument.
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't an int
	 */
	public function getInt (string $key) : int
	{
		$value = $this->get($key);

		if (!\is_int($value))
		{
			throw InvalidArgumentTypeException::create($key, $value, "int");
		}

		return $value;
	}


	/**
	 * Returns an optional int argument.
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't an int / null
	 */
	public function getOptionalInt (string $key) : ?int
	{
		$value = $this->getOptional($key);

		if (null !== $value && !\is_int($value))
		{
			throw InvalidArgumentTypeException::create($key, $value, "int / null");
		}

		return $value;
	}

	/**
	 * Returns a non-optional bool argument.
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't a bool
	 */
	public function getBool (string $key) : bool
	{
		$value = $this->get($key);

		if (true !== $value && false !== $value)
		{
			throw InvalidArgumentTypeException::create($key, $value, "bool");
		}

		return $value;
	}

	/**
	 * Returns an optional bool argument.
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't a bool / null
	 */
	public function getOptionalBool (string $key) : ?bool
	{
		$value = $this->getOptional($key);

		if (null !== $value && true !== $value && false !== $value)
		{
			throw InvalidArgumentTypeException::create($key, $value, "bool / null");
		}

		return $value;
	}

	/**
	 * Returns a non-optional float argument.
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't a float
	 */
	public function getFloat (string $key) : float
	{
		$value = $this->get($key);

		if (!\is_float($value))
		{
			throw InvalidArgumentTypeException::create($key, $value, "float");
		}

		return $value;
	}

	/**
	 * Returns an optional float argument.
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't a float / null
	 */
	public function getOptionalFloat (string $key) : ?float
	{
		$value = $this->getOptional($key);

		if (null !== $value && !\is_float($value))
		{
			throw InvalidArgumentTypeException::create($key, $value, "float / null");
		}

		return $value;
	}

	/**
	 * Returns a non-optional enum value of the given enum type.
	 *
	 * @template T of \BackedEnum
	 *
	 * @param class-string<T> $enumClass
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't an enum value of the given type
	 *
	 * @return T
	 */
	public function getEnum (string $key, string $enumClass) : \BackedEnum
	{
		$value = $this->get($key);

		if (!\is_int($value) && !\is_string($value))
		{
			throw InvalidArgumentTypeException::create(
				$key,
				$value,
				"enum of type {$enumClass}",
			);
		}

		try
		{
			return $enumClass::from($value);
		}
		catch (\ValueError|\TypeError $exception)
		{
			throw InvalidArgumentTypeException::create(
				$key,
				$value,
				"enum of type {$enumClass}",
				$exception,
			);
		}
	}

	/**
	 * Returns an optional enum value of the given enum type.
	 *
	 * @template T of \BackedEnum
	 *
	 * @param class-string<T> $enumClass
	 *
	 * @throws InvalidArgumentTypeException if value isn't an enum value of the given type / null
	 *
	 * @return T|null
	 */
	public function getOptionalEnum (string $key, string $enumClass) : ?\BackedEnum
	{
		$value = $this->getOptional($key);

		if (null === $value)
		{
			return null;
		}

		if (!\is_int($value) && !\is_string($value))
		{
			throw InvalidArgumentTypeException::create(
				$key,
				$value,
				"enum of type {$enumClass} / null",
			);
		}

		try
		{
			return $enumClass::from($value);
		}
		catch (\ValueError|\TypeError $exception)
		{
			throw InvalidArgumentTypeException::create(
				$key,
				$value,
				"enum of type {$enumClass} / null",
				$exception,
			);
		}
	}

	/**
	 * Returns a non-optional object value of the given class.
	 *
	 * @template T of object
	 *
	 * @param class-string<T> $className
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't an object of the given type
	 *
	 * @return T
	 */
	public function getObject (string $key, string $className) : object
	{
		$value = $this->get($key);

		if (!\is_object($value) || !\is_a($value, $className))
		{
			throw InvalidArgumentTypeException::create(
				$key,
				$value,
				"class of type {$className}",
			);
		}

		return $value;
	}

	/**
	 * Returns an optional object value of the given class.
	 *
	 * @template T of object
	 *
	 * @param class-string<T> $className
	 *
	 * @throws InvalidArgumentTypeException if value isn't an object of the given type / null
	 *
	 * @return T|null
	 */
	public function getOptionalObject (string $key, string $className) : ?object
	{
		$value = $this->getOptional($key);

		if (null === $value)
		{
			return null;
		}

		if (!\is_object($value) || !\is_a($value, $className))
		{
			throw InvalidArgumentTypeException::create(
				$key,
				$value,
				"class of type {$className} / null",
			);
		}

		return $value;
	}

	/**
	 * Returns a non-optional array argument.
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't an array
	 */
	public function getArray (string $key) : array
	{
		$value = $this->get($key);

		if (!\is_array($value))
		{
			throw InvalidArgumentTypeException::create($key, $value, "array");
		}

		return $value;
	}

	/**
	 * Returns an optional array argument.
	 *
	 * @throws InvalidArgumentTypeException if key doesn't exist or value isn't an array / null
	 */
	public function getOptionalArray (string $key) : ?array
	{
		$value = $this->getOptional($key);

		if (null !== $value && !\is_array($value))
		{
			throw InvalidArgumentTypeException::create($key, $value, "array / null");
		}

		return $value;
	}

	/**
	 * Returns whether an argument with the given key exists.
	 */
	public function has (string $key) : bool
	{
		return \array_key_exists($key, $this->arguments);
	}

	/**
	 *
	 */
	public function getIterator () : \Traversable
	{
		return new \ArrayIterator($this->arguments);
	}

	/**
	 *
	 */
	public function count () : int
	{
		return \count($this->arguments);
	}


}
