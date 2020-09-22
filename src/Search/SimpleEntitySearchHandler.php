<?php declare(strict_types=1);

namespace Torr\Rad\Search;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Torr\Rad\Search\Tokenizer\SimpleTokenizer;

/**
 * Adds search parameters to a query builder
 */
final class SimpleEntitySearchHandler
{
	private SimpleTokenizer $tokenizer;


	/**
	 */
	public function __construct (SimpleTokenizer $tokenizer)
	{
		$this->tokenizer = $tokenizer;
	}


	/**
	 * Applies the search constraint to the given query build
	 */
	public function applySearch (
		QueryBuilder $queryBuilder,
		?string $input,
		array $fieldsToSearch,
		bool $mode = SimpleTokenizer::MODE_PREFIX
	) : QueryBuilder
	{
		$query = $this->tokenizer->transformInput($input, $mode);

		if ("" === $query)
		{
			return $queryBuilder;
		}

		$constraint = new Orx();

		foreach ($fieldsToSearch as $field)
		{
			$constraint->add(
				new Comparison($field, "LIKE", ":__searchQueryTerm")
			);
		}

		return $queryBuilder
			->andWhere($constraint)
			->setParameter("__searchQueryTerm", $query);
	}
}
