<?php declare(strict_types=1);

namespace Torr\Rad\Pagination\Exception;

use Torr\Rad\Exception\RadException;

final class InvalidPaginationException extends \InvalidArgumentException implements RadException
{
}
