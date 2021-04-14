<?php declare(strict_types=1);

namespace Torr\Rad\Command;

use Torr\Cli\Console\Style\TorrStyle;

\trigger_deprecation(
	"21torr/rad",
	"1.1.2",
	"The TorrCliStyle from the rad bundle is deprecated. Use TorrStyle from `21torr/cli` instead."
);

/**
 * @deprecated Use TorrStyle from `21torr/cli` instead.
 */
final class TorrCliStyle extends TorrStyle
{
}
