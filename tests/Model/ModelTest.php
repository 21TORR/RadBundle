<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Model;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Tests\Torr\Rad\Fixtures\ExampleEntity;
use Torr\Rad\Entity\Interfaces\EntityInterface;
use Torr\Rad\Entity\Traits\IdTrait;
use Torr\Rad\Entity\Traits\TimestampsTrait;
use Torr\Rad\Model\Model;

final class ModelTest extends TestCase
{
	/**
	 * Tests integration of {@see Model::add()} method
	 */
	public function testAdd () : void
	{
		$manager = $this->createMock(EntityManagerInterface::class);
		$registry = $this->createMock(ManagerRegistry::class);

		$registry
			->method("getManager")
			->willReturn($manager);

		$entity = new ExampleEntity();

		$manager
			->expects(self::once())
			->method("persist")
			->with($entity);

		$model = new class ($registry) extends Model {};
		$model->add($entity);
	}


	/**
	 * Tests integration of {@see Model::update()} method
	 */
	public function testUpdate () : void
	{
		$manager = $this->createMock(EntityManagerInterface::class);
		$registry = $this->createMock(ManagerRegistry::class);

		$registry
			->method("getManager")
			->willReturn($manager);

		$model = new class ($registry) extends Model {};

		// test with timestamps
		$entityWithTimestamps = $this->createMock(ExampleEntity::class);
		$entityWithTimestamps
			->expects(self::once())
			->method("markAsModified");
		$model->update($entityWithTimestamps);

		// test without timestamps
		$model->update($this->createMock(EntityInterface::class));
		self::assertTrue(true, "should not have crashed");
	}


	/**
	 * Tests integration of {@see Model::remove()} method
	 */
	public function testRemove () : void
	{
		$manager = $this->createMock(EntityManagerInterface::class);
		$registry = $this->createMock(ManagerRegistry::class);

		$registry
			->method("getManager")
			->willReturn($manager);

		$entity = new ExampleEntity();

		$manager
			->expects(self::once())
			->method("remove")
			->with($entity);

		$model = new class ($registry) extends Model {};
		$model->remove($entity);
	}


	/**
	 * Tests integration of {@see Model::flush()} method
	 */
	public function testFlush () : void
	{
		$manager = $this->createMock(EntityManagerInterface::class);
		$registry = $this->createMock(ManagerRegistry::class);

		$registry
			->method("getManager")
			->willReturn($manager);

		$manager
			->expects(self::once())
			->method("flush");

		$model = new class ($registry) extends Model {};
		$model->flush();
	}

	private function createEntity () : EntityInterface
	{
		return new class implements EntityInterface
		{
			use IdTrait;
			use TimestampsTrait;
		};
	}
}
