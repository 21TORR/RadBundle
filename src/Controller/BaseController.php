<?php declare(strict_types=1);

namespace Torr\Rad\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Torr\Rad\Exception\Request\InvalidJsonRequestException;
use Torr\Rad\Form\FormErrorNormalizer;

/**
 * Base controller (backend + frontend).
 */
abstract class BaseController extends AbstractController
{
	/**
	 * @template T of object
	 *
	 * @param class-string<T> $service
	 *
	 * @return T
	 */
	protected function getService (string $service) : object
	{
		// @phpstan-ignore-next-line return.type (The return value is fine, PHPStan doesn't know the generics)
		return $this->container->get($service);
	}

	/**
	 * Normalizes the errors from the given form.
	 *
	 * @protected
	 *
	 * @todo change to real `protected` in v4.0
	 *
	 * @return array<string, string[]>
	 */
	public function normalizeFormErrors (FormInterface $form, string $translationDomain = "validators") : array
	{
		return $this->getService(FormErrorNormalizer::class)->normalize($form, $translationDomain);
	}

	/**
	 * Returns the default logger.
	 *
	 * @protected
	 *
	 * @todo change to real `protected` in v4.0
	 */
	public function getLogger () : LoggerInterface
	{
		return $this->getService(LoggerInterface::class);
	}

	/**
	 * Fetches the data in the request body as JSON.
	 *
	 * @protected
	 *
	 * @todo change to real `protected` in v4.0
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

		try
		{
			return $request->getPayload()->all();
		}
		catch (JsonException $exception)
		{
			$this->getLogger()->error("Parsing JSON payload failed: {message}", [
				"message" => $exception->getMessage(),
				"exception" => $exception,
				"json" => (string) $request->getContent(),
			]);

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
