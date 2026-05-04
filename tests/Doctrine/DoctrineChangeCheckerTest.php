<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;
use Torr\Rad\Doctrine\DoctrineChangeChecker;

/**
 * @internal
 */
final class DoctrineChangeCheckerTest extends TestCase
{
	use ExpectDeprecationTrait;

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
	 *
	 * @group legacy
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

		$this->expectDeprecation("Since 21torr/rad 3.4.6: Using Torr\\Rad\\Doctrine\\DoctrineChangeChecker is deprecated and will be removed in v4.");
		// @phpstan-ignore-next-line
		$checker = new DoctrineChangeChecker($registry);
		// @phpstan-ignore-next-line
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
	 *
	 * @group legacy
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

		$this->expectDeprecation("Since 21torr/rad 3.4.6: Using Torr\\Rad\\Doctrine\\DoctrineChangeChecker is deprecated and will be removed in v4.");
		// @phpstan-ignore-next-line
		$checker = new DoctrineChangeChecker($registry);
		// @phpstan-ignore-next-line
		self::assertSame($expected, $checker->hasContentChanged());
	}
}
