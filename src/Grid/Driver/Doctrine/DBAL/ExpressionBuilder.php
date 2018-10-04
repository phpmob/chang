<?php

declare(strict_types=1);

namespace Chang\Grid\Driver\Doctrine\DBAL;

use Doctrine\DBAL\Query\QueryBuilder;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;

final class ExpressionBuilder implements ExpressionBuilderInterface
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var string
     */
    private $tableAlias;

    public function __construct(QueryBuilder $queryBuilder, string $tableAlias)
    {
        $this->queryBuilder = $queryBuilder;
        $this->tableAlias = $tableAlias;
    }

    /**
     * {@inheritdoc}
     */
    public function andX(...$expressions)
    {
        return $this->queryBuilder->expr()->andX(...$expressions);
    }

    /**
     * {@inheritdoc}
     */
    public function orX(...$expressions)
    {
        return $this->queryBuilder->expr()->orX(...$expressions);
    }

    /**
     * {@inheritdoc}
     */
    public function comparison(string $field, string $operator, $value)
    {
        return $this->queryBuilder->expr()->comparison($this->getFieldName($field), $operator, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function equals(string $field, $value)
    {
        $parameterName = $this->getParameterName($field);
        $this->queryBuilder->setParameter($parameterName, $value);

        return $this->queryBuilder->expr()->eq($this->getFieldName($field), ':' . $parameterName);
    }

    /**
     * {@inheritdoc}
     */
    public function notEquals(string $field, $value)
    {
        $parameterName = $this->getParameterName($field);
        $this->queryBuilder->setParameter($parameterName, $value);

        return $this->queryBuilder->expr()->neq($this->getFieldName($field), ':' . $parameterName);
    }

    /**
     * {@inheritdoc}
     */
    public function lessThan(string $field, $value)
    {
        $parameterName = $this->getParameterName($field);

        $this->queryBuilder->andWhere($this->getFieldName($field) . ' < :' . $parameterName)
            ->setParameter($parameterName, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function lessThanOrEqual(string $field, $value)
    {
        $parameterName = $this->getParameterName($field);

        $this->queryBuilder
            ->andWhere($this->getFieldName($field) . ' =< :' . $parameterName)
            ->setParameter($parameterName, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function greaterThan(string $field, $value)
    {
        $parameterName = $this->getParameterName($field);

        $this->queryBuilder
            ->andWhere($this->getFieldName($field) . ' > :' . $parameterName)
            ->setParameter($parameterName, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function greaterThanOrEqual(string $field, $value)
    {
        $parameterName = $this->getParameterName($field);

        $this->queryBuilder->andWhere($this->getFieldName($field) . ' => :%s' . $parameterName)
            ->setParameter($parameterName, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function in(string $field, array $values)
    {
        return $this->queryBuilder->expr()->in($this->getFieldName($field), $values);
    }

    /**
     * {@inheritdoc}
     */
    public function notIn(string $field, array $values)
    {
        return $this->queryBuilder->expr()->notIn($this->getFieldName($field), $values);
    }

    /**
     * {@inheritdoc}
     */
    public function isNull(string $field)
    {
        return $this->queryBuilder->expr()->isNull($this->getFieldName($field));
    }

    /**
     * {@inheritdoc}
     */
    public function isNotNull(string $field)
    {
        return $this->queryBuilder->expr()->isNotNull($this->getFieldName($field));
    }

    /**
     * {@inheritdoc}
     */
    public function like(string $field, string $pattern)
    {
        return $this->queryBuilder->expr()->like($this->getFieldName($field), $this->queryBuilder->expr()->literal($pattern));
    }

    /**
     * {@inheritdoc}
     */
    public function notLike(string $field, string $pattern)
    {
        return $this->queryBuilder->expr()->notLike($this->getFieldName($field), $this->queryBuilder->expr()->literal($pattern));
    }

    /**
     * {@inheritdoc}
     */
    public function orderBy(string $field, string $direction)
    {
        return $this->queryBuilder->orderBy($this->getFieldName($field), $direction);
    }

    /**
     * {@inheritdoc}
     */
    public function addOrderBy(string $field, string $direction)
    {
        return $this->queryBuilder->addOrderBy($this->getFieldName($field), $direction);
    }

    /**
     * @param string $field
     *
     * @return string
     */
    private function getFieldName(string $field): string
    {
        if (preg_match('/^:/', $field)) {
            return str_replace(':', '', $field);
        }

        if (false === strpos($field, '.')) {
            return $this->tableAlias . '.' . $field;
        }

        return $field;
    }

    /**
     * @param string $field
     *
     * @return string
     */
    private function getParameterName(string $field): string
    {
        $parameterName = str_replace('.', '_', $field);

        $i = 1;
        while ($this->hasParameterName($parameterName)) {
            $parameterName .= $i;
        }

        return $parameterName;
    }

    /**
     * @param string $parameterName
     *
     * @return bool
     */
    private function hasParameterName(string $parameterName): bool
    {
        return null !== $this->queryBuilder->getParameter($parameterName);
    }
}
