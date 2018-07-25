<?php

declare(strict_types=1);

namespace Chang\Messenger\Transport;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class AmqpSpoolSubscriber implements EventSubscriberInterface
{
    /**
     * @var AmqpSpoolTransportFactoryInterface
     */
    private $factory;

    public function __construct(AmqpSpoolTransportFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        $events = [];

        if (class_exists(KernelEvents::class)) {
            $events[KernelEvents::TERMINATE] = 'flush';
        }

        if (class_exists(ConsoleEvents::class)) {
            $events[ConsoleEvents::TERMINATE] = 'flush';
        }

        return $events;
    }

    public function flush()
    {
        foreach ($this->factory->getTransports() as $transport) {
            $transport->flush();
        }
    }
}
