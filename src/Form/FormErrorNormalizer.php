<?php declare(strict_types=1);

namespace Torr\Rad\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class that normalizes form errors to a nested array containing the messages.
 */
final class FormErrorNormalizer
{
	private const GLOBAL_KEY = "__global";
	private TranslatorInterface $translator;


	/**
	 */
	public function __construct (TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}


	/**
	 * Adds all validation errors of nested fields to the list of errors.
	 */
	private function normalizeNested (array &$errors, FormInterface $parent, string $prefix, string $translationDomain) : void
	{
		foreach ($parent->all() as $child)
		{
			$key = \ltrim("{$prefix}{$child->getName()}");

			foreach ($child->getErrors() as $childError)
			{
				$errors[$key][] = $this->translator->trans($childError->getMessage(), [], $translationDomain);
			}

			$this->normalizeNested($errors, $child, "{$key}_", $translationDomain);
		}
	}


	/**
	 * Fetches all validation errors of the form and returns it as nested JSON structure.
	 *
	 * @return array<string, string[]>
	 */
	public function normalize (FormInterface $form, string $translationDomain = "validators") : array
	{
		$errors = [];
		$formName = $form->getName();
		$prefix = !empty($formName) ? "{$formName}_" : "";

		// add nested errors
		$this->normalizeNested($errors, $form, $prefix, $translationDomain);

		// add global errors (the errors on the root form, or that bubbled up)
		foreach ($form->getErrors() as $error)
		{
			$errors[self::GLOBAL_KEY][] = $this->translator->trans($error->getMessage(), [], $translationDomain);
		}

		return $errors;
	}
}
