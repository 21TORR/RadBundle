<?php declare(strict_types=1);

namespace Torr\Rad\Command;

use Symfony\Component\HttpKernel\Profiler\Profiler;

/**
 * Helper class that eases implementation when building commands.
 */
final class CommandHelper
{
	private ?Profiler $profiler;


	/**
	 */
	public function __construct (?Profiler $profiler)
	{
		$this->profiler = $profiler;
	}


	/**
	 *
	 */
	public function startLongRunningCommand () : void
	{
		// disable symfony profiler
		if (null !== $this->profiler)
		{
			$this->profiler->disable();
		}

		// increase PHP limits
		\set_time_limit(0);
		\ini_set("memory_limit", "-1");
	}
}
