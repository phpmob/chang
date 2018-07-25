<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class FlushPusherSubscriber implements EventSubscriberInterface
{
    /**
     * @var SpoolSender
     */
    private $sender;

    public function __construct(SpoolSender $sender)
    {
        $this->sender = $sender;
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
        $this->sender->flush();
    }
}
