<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Fixtures;

use Torr\Rad\Entity\Interfaces\EntityInterface;
use Torr\Rad\Entity\Traits\IdTrait;
use Torr\Rad\Entity\Traits\TimestampsTrait;

/**
 */
class ExampleEntity implements EntityInterface
{
	use IdTrait;
	use TimestampsTrait;

	public function __construct (?int $id = null)
	{
		$this->id = $id;
	}
}
