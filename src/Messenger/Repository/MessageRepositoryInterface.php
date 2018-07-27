<?php

declare(strict_types=1);

namespace Chang\Messenger\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface;

interface MessageRepositoryInterface extends RepositoryInterface
{
    /**
     * @param UserInterface $user
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function createUserQueryBuilder(UserInterface $user, string $alias = 'o'): QueryBuilder;

    /**
     * @param UserInterface $user
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function createUnseenUserQueryBuilder(UserInterface $user, string $alias = 'o'): QueryBuilder;

    /**
     * @param UserInterface $user
     * @param string $alias
     *
     * @return Pagerfanta
     */
    public function createUserPaginator(UserInterface $user, string $alias = 'o'): Pagerfanta;

    /**
     * @param UserInterface $user
     * @param string $alias
     *
     * @return Pagerfanta
     */
    public function createUnseenUserPaginator(UserInterface $user, string $alias = 'o'): Pagerfanta;

    /**
     * @param UserInterface $user
     *
     * @return int
     */
    public function getUserTotal(UserInterface $user): int;

    /**
     * @param UserInterface $user
     *
     * @return int
     */
    public function getUserTotalSeen(UserInterface $user): int;
}
