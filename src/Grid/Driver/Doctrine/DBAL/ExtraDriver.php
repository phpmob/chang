<?php

declare(strict_types=1);

namespace Chang\Grid\Driver\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\DriverInterface;
use Sylius\Component\Grid\Parameters;

final class ExtraDriver implements DriverInterface
{
    public const NAME = 'doctrine/dbal-extra';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $tableAlias = 'o';

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSource(array $configuration, Parameters $parameters): DataSourceInterface
    {
        if (!array_key_exists('table', $configuration)) {
            throw new \InvalidArgumentException('"table" must be configured.');
        }

        if (null !== ($configuration['query_builder'] ?? null)) {
            $queryBuilder = $configuration['query_builder'];
        } else {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select($this->tableAlias . '.*')
                ->from($configuration['table'], $this->tableAlias);
        }

        foreach ($configuration['aliases'] as $alias => $column) {
            $queryBuilder->addSelect(sprintf('%s as %s', $column ?? ($this->tableAlias . '.' . $alias), $alias));
        }

        foreach ((array)($configuration['groups'] ?? []) as $groupBy) {
            $queryBuilder->addGroupBy($groupBy);
        }

        return new DataSource($queryBuilder, $this->tableAlias);
    }
}
