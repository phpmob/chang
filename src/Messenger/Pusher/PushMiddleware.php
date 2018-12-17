<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

use Chang\Messenger\Message\AbstractPushMessage;
use Chang\Messenger\Repository\DeviceRepositoryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class PushMiddleware implements MiddlewareInterface
{
    /**
     * @var SenderInterface
     */
    private $sender;

    /**
     * @var DeviceRepositoryInterface
     */
    private $repository;

    public function __construct(SenderInterface $sender, DeviceRepositoryInterface $repository)
    {
        $this->sender = $sender;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     *
     * @throws SenderException
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $msg = $message->getMessage();

        if (!$msg instanceof AbstractPushMessage) {
            return $stack->next()->handle($envelope, $stack);
        }

        if (0 === \count($envelope->all(ReceivedStamp::class))) {
            return $stack->next()->handle($envelope, $stack);
        }

        $rawMessage = $msg->getRawMessage();

        foreach ($rawMessage->getRecipients() as $recipient) {
            foreach ($this->repository->findUserEnableDevices($recipient->getUser()) as $device) {
                $this->sender->sendTo($device, $rawMessage);
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
