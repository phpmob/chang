<?php

declare(strict_types=1);

namespace Chang\Messenger\Doctrine\ORM;

use Chang\Messenger\Repository\DeviceRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\User\Model\UserInterface;

class DeviceRepository extends EntityRepository implements DeviceRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findUserEnableDevices(UserInterface $user): array
    {
        return (array)$this->createQueryBuilder('o')
            ->where('o.enabled = :enabled')
            ->andWhere('o.user = :user')
            ->setParameter('enabled', true)
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findUserDevices(UserInterface $user, string $platform = null)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->where('o.user = :user')
            ->setParameter('user', $user);

        if ($platform) {
            $queryBuilder
                ->andWhere('o.platform = :platform')
                ->setParameter('platform', $platform);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
