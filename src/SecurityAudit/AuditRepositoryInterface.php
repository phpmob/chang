<?php

declare(strict_types=1);

namespace Chang\SecurityAudit;

interface AuditRepositoryInterface
{
    /**
     * @param null|string $username
     *
     * @return mixed|\Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilderByUsername(?string $username);
}
