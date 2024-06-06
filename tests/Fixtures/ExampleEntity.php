<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Fixtures;

use Torr\Rad\Entity\EntityInterface;
use Torr\Rad\Entity\ModifiableEntityFieldsTrait;

use function Symfony\Component\Clock\now;

/**
 */
class ExampleEntity implements EntityInterface
{
	use ModifiableEntityFieldsTrait;

	public function __construct (?int $id = null)
	{
		$this->id = $id;
		$this->timeCreated = now();
	}
}
