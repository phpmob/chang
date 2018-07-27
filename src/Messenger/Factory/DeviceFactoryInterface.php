<?php

declare(strict_types=1);

namespace Chang\Messenger\Factory;

use Chang\Messenger\Model\DeviceInterface;
use Sylius\Component\User\Model\UserInterface;

interface DeviceFactoryInterface
{
    /**
     * @return DeviceInterface
     */
    public function createNew(): DeviceInterface;

    /**
     * @param UserInterface $user
     * @param string|null $clientIp
     *
     * @return DeviceInterface
     */
    public function createForUser(UserInterface $user, string $clientIp = null): DeviceInterface;
}
