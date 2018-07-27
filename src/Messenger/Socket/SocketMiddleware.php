<?php

declare(strict_types=1);

namespace Chang\Messenger\Socket;

use Chang\Messenger\Message\AbstractPushMessage;
use Symfony\Component\Messenger\Asynchronous\Transport\ReceivedMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\EnvelopeAwareInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

class SocketMiddleware implements MiddlewareInterface, EnvelopeAwareInterface
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $key;

    public function __construct(string $prefix, string $key = 'socket')
    {
        $this->prefix = $prefix;
        $this->key = $key;
    }

    /**
     * @param object|Envelope $message
     * @param callable $next
     *
     * @return mixed
     */
    public function handle($message, callable $next)
    {
        $msg = $message->getMessage();

        if (!$msg instanceof AbstractPushMessage) {
            return $next($message);
        }

        // ignore add `{$this->key}` extra when bus receive
        if (Envelope::wrap($message)->get(ReceivedMessage::class)) {
            return $next($message);
        }

        $msg->addExtra($this->key, [
            'prefix' => $this->prefix,
        ]);

        return $next($message);
    }
}
