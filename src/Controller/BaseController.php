<?php declare(strict_types=1);

namespace Torr\Rad\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Torr\Rad\Exception\Request\InvalidJsonRequestException;
use Torr\Rad\Form\FormErrorNormalizer;

/**
 * Base controller (backend + frontend).
 */
abstract class BaseController extends AbstractController
{
	/**
	 * Normalizes the errors from the given form.
	 *
	 * @protected
	 *
	 * @todo change to real `protected` in v3.0
	 */
	public function normalizeFormErrors (FormInterface $form, string $translationDomain = "validators") : array
	{
		$normalizer = $this->container->get(FormErrorNormalizer::class);
		\assert($normalizer instanceof FormErrorNormalizer);
		return $normalizer->normalize($form, $translationDomain);
	}

	/**
	 * Returns the default logger.
	 *
	 * @protected
	 *
	 * @todo change to real `protected` in v3.0
	 */
	public function getLogger () : LoggerInterface
	{
		$logger = $this->container->get(LoggerInterface::class);
		\assert($logger instanceof LoggerInterface);

		return $logger;
	}

	/**
	 * Fetches the data in the request body as JSON.
	 *
	 * @protected
	 *
	 * @todo change to real `protected` in v3.0
	 */
	public function fetchJsonRequestBody (Request $request, bool $allowInvalid = false) : array
	{
		if ("json" !== $request->getContentTypeFormat())
		{
			if ($allowInvalid)
			{
				return [];
			}

			throw new InvalidJsonRequestException("Expected JSON request content type.", 415);
		}

		$raw = \trim((string) $request->getContent());

		if ("" === $raw)
		{
			return [];
		}

		try
		{
			$data = \json_decode($raw, true, 512, \JSON_THROW_ON_ERROR);

			if (!\is_array($data))
			{
				throw new InvalidJsonRequestException(
					\sprintf("Invalid top level type in JSON payload. Must be array, is %s", \gettype($data)),
					400,
				);
			}

			return $data;
		}
		catch (\JsonException $exception)
		{
			throw new InvalidJsonRequestException(
				\sprintf("Parsing JSON payload failed: %s", $exception->getMessage()),
				400,
				$exception,
			);
		}
	}


	/**
	 * @inheritDoc
	 */
	public static function getSubscribedServices () : array
	{
		$services = parent::getSubscribedServices();
		$services[] = FormErrorNormalizer::class;
		$services[] = LoggerInterface::class;

		return $services;
	}
}
