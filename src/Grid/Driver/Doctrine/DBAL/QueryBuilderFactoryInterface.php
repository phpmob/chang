<?php

declare(strict_types=1);

namespace Chang\Grid\Driver\Doctrine\DBAL;

use Doctrine\DBAL\Query\QueryBuilder;

interface QueryBuilderFactoryInterface
{
    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder;
}
