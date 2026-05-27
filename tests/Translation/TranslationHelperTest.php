<?php declare(strict_types=1);

namespace Tests\Torr\Rad\Translation;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Torr\Rad\Translation\TranslationHelper;

/**
 * @internal
 */
final class TranslationHelperTest extends TestCase
{
	public function testTransDelegatesToTranslator () : void
	{
		$translator = $this->createMock(TranslatorInterface::class);
		$translator
			->expects(self::once())
			->method("trans")
			->with("greeting", ["name" => "Ada"], "messages", "de")
			->willReturn("Hallo Ada");

		$helper = new TranslationHelper($translator);

		self::assertSame("Hallo Ada", $helper->trans("greeting", ["name" => "Ada"], "messages", "de"));
	}

	public function testResolveReturnsStringAsIsForStringInput () : void
	{
		$translator = self::createStub(TranslatorInterface::class);
		$helper = new TranslationHelper($translator);

		self::assertSame("already translated", $helper->resolve("already translated", "de"));
	}

	public function testResolveTranslatesTranslatable () : void
	{
		$translator = self::createStub(TranslatorInterface::class);
		$helper = new TranslationHelper($translator);

		$translatable = new class() implements TranslatableInterface {
			public ?TranslatorInterface $receivedTranslator = null;
			public ?string $receivedLocale = null;

			public function trans (TranslatorInterface $translator, ?string $locale = null) : string
			{
				$this->receivedTranslator = $translator;
				$this->receivedLocale = $locale;

				return "translated value";
			}
		};

		self::assertSame("translated value", $helper->resolve($translatable, "fr"));
		self::assertSame($translator, $translatable->receivedTranslator);
		self::assertSame("fr", $translatable->receivedLocale);
	}

	public function testResolveReturnsNullForNull () : void
	{
		$translator = self::createStub(TranslatorInterface::class);
		$helper = new TranslationHelper($translator);

		self::assertNull($helper->resolve(null, "de"));
	}

	public function testResolveTranslatableUsesNullLocaleByDefault () : void
	{
		$translator = self::createStub(TranslatorInterface::class);
		$helper = new TranslationHelper($translator);

		$translatable = new class() implements TranslatableInterface {
			public ?string $receivedLocale = "initial";

			public function trans (TranslatorInterface $translator, ?string $locale = null) : string
			{
				$this->receivedLocale = $locale;

				return "translated without explicit locale";
			}
		};

		self::assertSame("translated without explicit locale", $helper->resolve($translatable));
		self::assertNull($translatable->receivedLocale);
	}

	/**
	 * Runtime assertion plus static type check for PHPStan:
	 * `resolve("...")` must be inferred as `string`, and `resolve(null)` as `null`.
	 */
	public function testResolveConditionalReturnTypeForPhpStan () : void
	{
		$translator = self::createStub(TranslatorInterface::class);
		$helper = new TranslationHelper($translator);

		$stringResult = $helper->resolve("value");
		$nullResult = $helper->resolve(null);

		self::assertSame("value", $stringResult);
		self::assertNull($nullResult);

		// These calls are the actual PHPStan checks for the conditional return type.
		self::expectString($stringResult);
		self::expectNull($nullResult);
	}

	private static function expectString (string $value) : void {}

	private static function expectNull (null $value) : void {}
}
