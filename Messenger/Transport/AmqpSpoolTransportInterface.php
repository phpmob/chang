<?php

declare(strict_types=1);

namespace Chang\Messenger\Transport;

use Symfony\Component\Messenger\Transport\TransportInterface;

interface AmqpSpoolTransportInterface extends TransportInterface
{
    /**
     * Flush messages
     */
    public function flush(): void;
}
