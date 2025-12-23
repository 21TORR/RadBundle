<?php declare(strict_types=1);

namespace Torr\Rad\Exception\Import;

use Torr\Rad\Exception\RadException;

/**
 * @final
 */
class InvalidImportDataException extends \InvalidArgumentException implements RadException
{
}
