<?php

declare(strict_types=1);

namespace Chang\Messenger\Transport;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpTransport;
use Symfony\Component\Messenger\Transport\AmqpExt\Connection;
use Symfony\Component\Messenger\Transport\Serialization\DecoderInterface;
use Symfony\Component\Messenger\Transport\Serialization\EncoderInterface;

class AmqpSpoolTransport extends AmqpTransport implements AmqpSpoolTransportInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Envelope[]
     */
    private $messages = [];

    public function __construct(EncoderInterface $encoder, DecoderInterface $decoder, Connection $connection, LoggerInterface $logger = null)
    {
        parent::__construct($encoder, $decoder, $connection);

        $this->logger = $logger;
    }

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
            try {
                parent::send($message);
            } catch (\AMQPException $e) {
                $this->logger && $this->logger->error($e->getMessage());
            }
        }

        $this->messages = [];
    }
}
