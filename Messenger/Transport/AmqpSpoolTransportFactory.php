<?php

declare(strict_types=1);

namespace Chang\Messenger\Transport;

use Symfony\Component\Messenger\Transport\AmqpExt\Connection;
use Symfony\Component\Messenger\Transport\Serialization\DecoderInterface;
use Symfony\Component\Messenger\Transport\Serialization\EncoderInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class AmqpSpoolTransportFactory implements AmqpSpoolTransportFactoryInterface
{
    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var AmqpSpoolTransportInterface[]
     */
    private $transports = [];

    public function __construct(EncoderInterface $encoder, DecoderInterface $decoder, bool $debug)
    {
        $this->encoder = $encoder;
        $this->decoder = $decoder;
        $this->debug = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function createTransport(string $dsn, array $options): TransportInterface
    {
        $this->transports[] = $transport = new AmqpSpoolTransport(
            $this->encoder, $this->decoder, Connection::fromDsn($dsn, $options, $this->debug)
        );

        return $transport;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'amqps://');
    }

    /**
     * {@inheritdoc}
     */
    public function getTransports(): array
    {
        return $this->transports;
    }
}
