<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

use Chang\Messenger\Message\AbstractPushMessage;
use Chang\Messenger\Repository\DeviceRepositoryInterface;
use Symfony\Component\Messenger\Asynchronous\Transport\ReceivedMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\EnvelopeAwareInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

class PushMiddleware implements MiddlewareInterface, EnvelopeAwareInterface
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
     * @param object|Envelope $message
     * @param callable $next
     *
     * @return mixed
     * @throws SenderException
     */
    public function handle($message, callable $next)
    {
        $msg = $message->getMessage();

        if (!$msg instanceof AbstractPushMessage) {
            return $next($message);
        }

        if (Envelope::wrap($message)->get(ReceivedMessage::class)) {
            return $next($message);
        }

        $rawMessage = $msg->getRawMessage();

        foreach ($rawMessage->getRecipients() as $recipient) {
            foreach ($this->repository->findUserEnableDevices($recipient->getUser()) as $device) {
                $this->sender->sendTo($device, $rawMessage);
            }
        }

        return $next($message);
    }
}
