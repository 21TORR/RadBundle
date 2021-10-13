<?php declare(strict_types=1);

namespace Torr\Rad\Command;

use Torr\Cli\Command\CommandHelper as CliCommandHelper;

/**
 * Helper class that eases implementation when building commands.
 */
\trigger_deprecation(
	"21torr/rad",
	"1.1.2",
	"The CommandHelper from the rad bundle is deprecated. Use CommandHelper from `21torr/cli` directly instead.",
);

/**
 * @deprecated UseCommandHelper from `21torr/cli` directly instead.
 */
final class CommandHelper extends CliCommandHelper
{
}
