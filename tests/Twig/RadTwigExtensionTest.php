<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Twig;

use PHPUnit\Framework\TestCase;
use Torr\Rad\Html\DataContainer;
use Torr\Rad\Twig\RadTwigExtension;

class RadTwigExtensionTest extends TestCase
{
	public function testAppendToArray () : void
	{
		$extension = new RadTwigExtension(new DataContainer());
		$array = [
			"existing" => "old",
		];

		self::assertSame("old new", $extension->appendToArrayKey($array, "existing", "new")["existing"]);
		self::assertSame("new", $extension->appendToArrayKey($array, "missing", "new")["missing"]);
	}


	/**
	 * Tests that the exported names didn't change
	 */
	public function testIntegrations () : void
	{
		$expectedFunctions = [
			"data_container",
		];

		$expectedFilters = [
			"appendToArrayKey",
		];

		$foundFunctions = [];
		$foundFilters = [];

		$extension = new RadTwigExtension(new DataContainer());

		foreach ($extension->getFunctions() as $function)
		{
			$foundFunctions[] = $function->getName();
		}

		foreach ($extension->getFilters() as $filter)
		{
			$foundFilters[] = $filter->getName();
		}

		self::assertEmpty(\array_diff($expectedFunctions, $foundFunctions));
		self::assertEmpty(\array_diff($foundFunctions, $expectedFunctions));
		self::assertEmpty(\array_diff($expectedFilters, $foundFilters));
		self::assertEmpty(\array_diff($foundFilters, $expectedFilters));
	}
}
