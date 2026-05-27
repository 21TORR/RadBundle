<?php declare(strict_types=1);

namespace Torr\Rad\Translation;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @final
 * @api
 *
 * Translator helper that solves typical translation workflows
 */
readonly class TranslationHelper
{
	/**
	 */
	public function __construct (
		private TranslatorInterface $translator,
	) {}

	/**
	 * Regular translator, like {@see TranslatorInterface::trans()}
	 */
	public function trans (string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
	{
		return $this->translator->trans($id, $parameters, $domain, $locale);
	}

	/**
	 * Resolves a translatable value: if given a translatable, it will be translated. If given a string, it will return as-is.
	 *
	 * @phpstan-return ($value is null ? null : string)
	 */
	public function resolve (
		TranslatableInterface|string|null $value,
		?string $locale = null,
	) : ?string
	{
		if (null === $value)
		{
			return null;
		}

		return $value instanceof TranslatableInterface
			? $value->trans($this->translator, $locale)
			: $value;
	}
}
