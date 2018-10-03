<?php

declare(strict_types=1);

namespace Chang\Grid\Driver\Doctrine\DBAL;

use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Parameters;

final class DataSource implements DataSourceInterface
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var ExpressionBuilderInterface
     */
    private $expressionBuilder;

    /**
     * @var string
     */
    private $tableAlias;

    public function __construct(QueryBuilder $queryBuilder, string $tableAlias)
    {
        $this->queryBuilder = $queryBuilder;
        $this->tableAlias = $tableAlias;
        $this->expressionBuilder = new ExpressionBuilder($queryBuilder, $tableAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function restrict($expression, string $condition = DataSourceInterface::CONDITION_AND): void
    {
        switch ($condition) {
            case DataSourceInterface::CONDITION_AND:
                $this->queryBuilder->andWhere($expression);

                break;
            case DataSourceInterface::CONDITION_OR:
                $this->queryBuilder->orWhere($expression);

                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExpressionBuilder(): ExpressionBuilderInterface
    {
        return $this->expressionBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(Parameters $parameters)
    {
        $countQueryBuilderModifier = function (QueryBuilder $queryBuilder) {

            $sql = $queryBuilder->getSQL();

            $queryBuilder
                ->resetQueryParts(array_keys($queryBuilder->getQueryParts()))
                ->select(sprintf('COUNT(DISTINCT %s.id) AS total_results', $this->tableAlias))
                ->from(sprintf('(%s)', $sql), $this->tableAlias)
                ->setMaxResults(1)
            ;
        };

        $paginator = new Pagerfanta(new PagerAdapter($this->queryBuilder, $countQueryBuilderModifier));
        $paginator->setNormalizeOutOfRangePages(true);
        $paginator->setCurrentPage($parameters->get('page', 1));

        return $paginator;
    }
}
