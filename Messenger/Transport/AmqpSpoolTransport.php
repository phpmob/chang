<?php

declare(strict_types=1);

namespace Chang\Messenger\Transport;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpTransport;

class AmqpSpoolTransport extends AmqpTransport implements AmqpSpoolTransportInterface
{
    /**
     * @var Envelope[]
     */
    private $messages = [];

    /**
     * {@inheritdoc}
     */
    public function send(Envelope $envelope): void
    {
        $this->messages[] = $envelope;
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
        foreach ($this->messages as $message) {
            parent::send($message);
        }

        $this->messages = [];
    }
}
