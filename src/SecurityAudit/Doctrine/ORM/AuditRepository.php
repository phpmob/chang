<?php

declare(strict_types=1);

namespace Chang\SecurityAudit\Doctrine\ORM;

use Chang\SecurityAudit\AuditRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class AuditRepository extends EntityRepository implements AuditRepositoryInterface, RepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createQueryBuilderByUsername(?string $username)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        if ($username) {
            $queryBuilder
                ->where('o.username = :username')
                ->setParameter('username', $username);
        } else {
            $queryBuilder->where('0 = 1');
        }

        return $queryBuilder;
    }
}
