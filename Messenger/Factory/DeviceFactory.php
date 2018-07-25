<?php

declare(strict_types=1);

namespace Chang\Messenger\Factory;

use Chang\Messenger\Model\DeviceInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Model\UserInterface;

class DeviceFactory implements FactoryInterface, DeviceFactoryInterface
{
    /**
     * @var string
     */
    private $dataClass;

    public function __construct(string $dataClass)
    {
        $this->dataClass = $dataClass;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew(): DeviceInterface
    {
        $object = $this->dataClass;

        return new $object;
    }

    /**
     * {@inheritdoc}
     */
    public function createForUser(UserInterface $user, string $clientIp = null): DeviceInterface
    {
        $object = $this->createNew();
        $object->setUser($user);
        $object->setClientIp($clientIp);

        return $object;
    }
}
