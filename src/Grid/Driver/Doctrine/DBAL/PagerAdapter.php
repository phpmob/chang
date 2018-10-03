<?php

declare(strict_types=1);

namespace Chang\Grid\Driver\Doctrine\DBAL;

use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Exception\InvalidArgumentException;

class PagerAdapter implements AdapterInterface
{
    /**
     * @var int
     */
    private $fetchMode;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var callable
     */
    private $countQueryBuilderModifier;

    public function __construct(QueryBuilder $queryBuilder, $countQueryBuilderModifier, int $fetchMode = FetchMode::STANDARD_OBJECT)
    {
        if ($queryBuilder->getType() !== QueryBuilder::SELECT) {
            throw new InvalidArgumentException('Only SELECT queries can be paginated.');
        }

        if (!is_callable($countQueryBuilderModifier)) {
            throw new InvalidArgumentException('The count query builder modifier must be a callable.');
        }

        $this->queryBuilder = clone $queryBuilder;
        $this->countQueryBuilderModifier = $countQueryBuilderModifier;
        $this->fetchMode = $fetchMode;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        $qb = $this->prepareCountQueryBuilder();
        $result = $qb->execute()->fetchColumn();

        return (int)$result;
    }

    private function prepareCountQueryBuilder()
    {
        $qb = clone $this->queryBuilder;
        call_user_func($this->countQueryBuilderModifier, $qb);

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $qb = clone $this->queryBuilder;
        $result = $qb->setMaxResults($length)
            ->setFirstResult($offset)
            ->execute();

        return $result->fetchAll($this->fetchMode);
    }
}
