<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Model;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Tests\Torr\Rad\Fixtures\ExampleEntity;
use Torr\Rad\Entity\EntityInterface;
use Torr\Rad\Model\EntityModel;

/**
 * @internal
 */
final class EntityModelTest extends TestCase
{
	/**
	 * Tests integration of {@see EntityModel::add()} method
	 */
	public function testAdd () : void
	{
		$manager = $this->createMock(EntityManagerInterface::class);
		$entity = new ExampleEntity();

		$manager
			->expects(self::once())
			->method("persist")
			->with($entity);

		$model = new class($manager) extends EntityModel {};
		$model->add($entity);
	}

	/**
	 * Tests integration of {@see EntityModel::update()} method
	 */
	public function testUpdate () : void
	{
		// should not crash
		$this->expectNotToPerformAssertions();

		$manager = $this->createMock(EntityManagerInterface::class);
		$model = new class($manager) extends EntityModel {};

		// test with timestamps
		$entityWithTimestamps = $this->createMock(ExampleEntity::class);
		$entityWithTimestamps
			->expects(self::once())
			->method("markAsModified");
		$model->update($entityWithTimestamps);

		// test without timestamps
		$model->update($this->createMock(EntityInterface::class));
	}

	/**
	 * Tests integration of {@see EntityModel::remove()} method
	 */
	public function testRemove () : void
	{
		$manager = $this->createMock(EntityManagerInterface::class);

		$entity = new ExampleEntity();

		$manager
			->expects(self::once())
			->method("remove")
			->with($entity);

		$model = new class($manager) extends EntityModel {};
		$model->remove($entity);
	}

	/**
	 * Tests integration of {@see EntityModel::flush()} method
	 */
	public function testFlush () : void
	{
		$manager = $this->createMock(EntityManagerInterface::class);

		$manager
			->expects(self::once())
			->method("flush");

		$model = new class($manager) extends EntityModel {};
		$model->flush();
	}
}
