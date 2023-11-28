<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use Torr\Rad\Doctrine\DoctrineChangeChecker;
use PHPUnit\Framework\TestCase;

class DoctrineChangeCheckerTest extends TestCase
{
	/**
	 *
	 */
	public static function provideLargeEntityChanges () : iterable
	{
		yield "empty" => [
			false,
		];

		yield "entity-insertions" => [
			true,
			["not empty"],
		];

		yield "entity-deletions" => [
			true,
			[],
			["not empty"],
		];

		yield "collection-updates" => [
			true,
			[],
			[],
			["not empty"],
		];

		yield "collection-deletions" => [
			true,
			[],
			[],
			[],
			["not empty"],
		];
	}

	/**
	 * @dataProvider provideLargeEntityChanges
	 */
	public function testLargeEntityChanges (
		bool $expected,
		array $entityInsertions = [],
		array $entityDeletions = [],
		array $collectionUpdates = [],
		array $collectionDeletions = [],
	) : void
	{
		$unitOfWork = $this->createMock(UnitOfWork::class);

		$unitOfWork
			->method("getScheduledEntityInsertions")
			->willReturn($entityInsertions);

		$unitOfWork
			->method("getScheduledEntityUpdates")
			->willReturn([]);

		$unitOfWork
			->method("getScheduledEntityDeletions")
			->willReturn($entityDeletions);

		$unitOfWork
			->method("getScheduledCollectionUpdates")
			->willReturn($collectionUpdates);

		$unitOfWork
			->method("getScheduledCollectionDeletions")
			->willReturn($collectionDeletions);

		$entityManager = $this->createMock(EntityManagerInterface::class);
		$entityManager
			->method("getUnitOfWork")
			->willReturn($unitOfWork);

		$registry = $this->createMock(ManagerRegistry::class);
		$registry
			->method("getManager")
			->willReturn($entityManager);

		$checker = new DoctrineChangeChecker($registry);
		self::assertSame($expected, $checker->hasContentChanged());
	}


	public static function provideChangesets () : iterable
	{
		yield "only time modified" => [false, [
			"timeModified" => [/* ... */],
		]];

		yield "other" => [true, [
			"other" => [/* ... */],
		]];

		yield "other with time modified" => [true, [
			"other" => [/* ... */],
			"timeModified" => [/* ... */],
		]];
	}


	/**
	 * @dataProvider provideChangesets
	 */
	public function testChangesets (
		bool $expected,
		array $changeset,
	) : void
	{
		$unitOfWork = $this->createMock(UnitOfWork::class);
		$entity = new \stdClass();

		$unitOfWork
			->method("getScheduledEntityInsertions")
			->willReturn([]);

		$unitOfWork
			->method("getScheduledEntityUpdates")
			->willReturn([$entity]);

		$unitOfWork
			->method("getScheduledEntityDeletions")
			->willReturn([]);

		$unitOfWork
			->method("getScheduledCollectionUpdates")
			->willReturn([]);

		$unitOfWork
			->method("getScheduledCollectionDeletions")
			->willReturn([]);

		$unitOfWork
			->method("getEntityChangeSet")
			->with($entity)
			->willReturn($changeset);

		$entityManager = $this->createMock(EntityManagerInterface::class);
		$entityManager
			->method("getUnitOfWork")
			->willReturn($unitOfWork);

		$registry = $this->createMock(ManagerRegistry::class);
		$registry
			->method("getManager")
			->willReturn($entityManager);

		$checker = new DoctrineChangeChecker($registry);
		self::assertSame($expected, $checker->hasContentChanged());
	}
}
