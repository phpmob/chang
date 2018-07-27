<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

use Chang\Messenger\Model\DeviceInterface;
use Chang\Messenger\Model\MessageInterface;

interface SenderInterface
{
    /**
     * @param DeviceInterface $device
     * @param MessageInterface $message
     * @param array $options
     *
     * @throws SenderException
     */
    public function sendTo(DeviceInterface $device, MessageInterface $message, array $options = []): void;
}
