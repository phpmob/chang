<?php

declare(strict_types=1);

namespace Chang\Messenger\Repository;

use Chang\Messenger\Model\DeviceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface;

interface DeviceRepositoryInterface extends RepositoryInterface
{
    /**
     * @param UserInterface $user
     *
     * @return DeviceInterface[]
     */
    public function findUserEnableDevices(UserInterface $user): array;

    /**
     * @param UserInterface $user
     * @param string|null $platform
     *
     * @return DeviceInterface[]
     */
    public function findUserDevices(UserInterface $user, string $platform = null);
}
