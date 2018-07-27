<?php

declare(strict_types=1);

namespace Chang\Messenger\Doctrine\ORM;

use Chang\Messenger\Repository\MessageRepositoryInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\User\Model\UserInterface;

class MessageRepository extends EntityRepository implements MessageRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createUserQueryBuilder(UserInterface $user, string $alias = 'o'): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder($alias);
        $expr = $queryBuilder->expr();

        $queryBuilder
            ->addSelect('r')
            ->addSelect('v')
            ->leftJoin($alias . '.recipients', 'r', Join::WITH, 'r.user = :rUser')
            ->leftJoin($alias . '.viewers', 'v', Join::WITH, 'v.user = :vUser')
            ->where($expr->orX(
                'r.user = :user',
                $alias . '.global = :global'
            ))
            ->setParameter('user', $user)
            ->setParameter('rUser', $user)
            ->setParameter('vUser', $user)
            ->setParameter('global', true);

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function createUserPaginator(UserInterface $user, string $alias = 'o'): Pagerfanta
    {
        return $this->getPaginator($this->createUserQueryBuilder($user, $alias));
    }

    /**
     * {@inheritdoc}
     */
    public function createUnseenUserQueryBuilder(UserInterface $user, string $alias = 'o'): QueryBuilder
    {
        $queryBuilder = $this->createUserQueryBuilder($user, $alias);

        return $queryBuilder
            ->andWhere($queryBuilder->expr()->isNull('v.user'));
    }

    /**
     * {@inheritdoc}
     */
    public function createUnseenUserPaginator(UserInterface $user, string $alias = 'o'): Pagerfanta
    {
        return $this->getPaginator($this->createUnseenUserQueryBuilder($user, $alias));
    }

    /**
     * {@inheritdoc}
     */
    public function getUserTotal(UserInterface $user): int
    {
        return $this->createUserPaginator($user)->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getUserTotalSeen(UserInterface $user): int
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->innerJoin('o.viewers', 'v')
            ->where('v.user = :user')
            ->setParameter('user', $user);

        return $this->getPaginator($queryBuilder)->count();
    }
}
