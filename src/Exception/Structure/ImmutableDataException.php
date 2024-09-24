<?php declare(strict_types=1);

namespace Torr\Rad\Exception\Structure;

use Torr\Rad\Exception\RadException;

/**
 * @final
 */
class ImmutableDataException extends \BadMethodCallException implements RadException
{
}
