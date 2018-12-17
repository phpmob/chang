<?php

declare(strict_types=1);

namespace Chang\Messenger\Middleware;

use Chang\Messenger\Message\AbstractMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

// TODO: check both send and receive handled.
class ExpiredMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if (!$message instanceof AbstractMessage) {
            return $stack->next()->handle($envelope, $stack);
        }

        if ($message->isExpired()) {
            return $stack->next()->handle($envelope, $stack);
        }

        if (array_key_exists('expired_at', (array)$message->body) && !empty($message->body['expired_at'])) {
            if (new \DateTime('@' . $message->body['expired_at']) < new \DateTime()) {
                $message->setExpired(true);
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
