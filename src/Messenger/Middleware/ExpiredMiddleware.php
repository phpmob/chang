<?php

declare(strict_types=1);

namespace Chang\Messenger\Middleware;

use Chang\Messenger\Message\AbstractMessage;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

// no `EnvelopeAwareInterface` to handle both send and receive.
class ExpiredMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        if (!$message instanceof AbstractMessage) {
            return $next($message);
        }

        if ($message->isExpired()) {
            return $message;
        }

        if (array_key_exists('expired_at', (array)$message->body) && !empty($message->body['expired_at'])) {
            if (new \DateTime('@' . $message->body['expired_at']) < new \DateTime()) {
                $message->setExpired(true);

                return $message;
            }
        }

        return $next($message);
    }
}
