<?php declare(strict_types=1);

namespace Torr\Rad\Pagination;

use Torr\Rad\Pagination\Exception\InvalidPaginationException;

class Pagination
{
	/**
	 * ATTENTION:
	 * The {@see self::$currentPage} value is not validated. This is the raw values as passed from the outside.
	 * If you need a valid and sanitized "current page" value, use the getter {@see self::getCurrentPage()}.
	 *
	 * This raw value is stored here, so that it can be passed when cloning the pagination.
	 */
	private int $currentPage;
	private int $numberOfItems;
	private int $perPage;
	private int $maxPage;


	/**
	 */
	public function __construct (int $currentPage, int $perPage = 50, int $numberOfItems = 0)
	{
		if ($perPage <= 0)
		{
			throw new InvalidPaginationException("Per page must be > 0.");
		}

		if ($numberOfItems < 0)
		{
			throw new InvalidPaginationException("Number of items must be > 0.");
		}

		$this->currentPage = $currentPage;
		$this->numberOfItems = $numberOfItems;
		$this->perPage = $perPage;
		$this->maxPage = (int) \max(1, (int) \ceil($numberOfItems / $perPage));
	}



	/**
	 * Returns the validated and sanitized current page.
	 */
	public function getCurrentPage () : int
	{
		// if < min page
		if ($this->currentPage < 1)
		{
			return 1;
		}

		// if > max page
		if ($this->currentPage > $this->maxPage)
		{
			return $this->maxPage;
		}

		return $this->currentPage;
	}


	/**
	 */
	public function getMinPage () : int
	{
		return 1;
	}


	/**
	 */
	public function getMaxPage () : int
	{
		return $this->maxPage;
	}


	/**
	 */
	public function getPerPage () : int
	{
		return $this->perPage;
	}


	/**
	 */
	public function getNextPage () : ?int
	{
		$next = $this->getCurrentPage() + 1;

		return $next <= $this->maxPage
			? $next
			: null;
	}


	/**
	 */
	public function getPreviousPage () : ?int
	{
		$prev = $this->getCurrentPage() - 1;

		return $prev >= 1
			? $prev
			: null;
	}


	/**
	 */
	public function getNumberOfItems () : int
	{
		return $this->numberOfItems;
	}


	/**
	 * Returns the offset for usage in database calculations.
	 *
	 * This method is overwritable to allow pages with a different number of items.
	 */
	public function getDatabaseRowOffset () : int
	{
		return ($this->getCurrentPage() - $this->getMinPage()) * $this->getPerPage();
	}


	/**
	 */
	public function toArray () : array
	{
		return [
			"current" => $this->getCurrentPage(),
			"min" => $this->getMinPage(),
			"max" => $this->maxPage,
			"next" => $this->getNextPage(),
			"prev" => $this->getPreviousPage(),
			"perPage" => $this->perPage,
			"total" => $this->numberOfItems,
			"valid" => $this->isValid(),
		];
	}


	/**
	 * @return Pagination
	 */
	public function withNumberOfItems (int $numberOfItems) : self
	{
		return new self($this->currentPage, $this->perPage, $numberOfItems);
	}


	/**
	 * Returns whether the configuration with the current page is valid (so whether the current page is in the
	 * range between [min; max]).
	 */
	public function isValid () : bool
	{
		return $this->getCurrentPage() === $this->currentPage;
	}
}
