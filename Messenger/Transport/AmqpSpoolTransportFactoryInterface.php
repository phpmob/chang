<?php

declare(strict_types=1);

namespace Chang\Messenger\Transport;

use Symfony\Component\Messenger\Transport\TransportFactoryInterface;

interface AmqpSpoolTransportFactoryInterface extends TransportFactoryInterface
{
    /**
     * @return AmqpSpoolTransportInterface[]
     */
    public function getTransports(): array;
}
