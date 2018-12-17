<?php

declare(strict_types=1);

namespace Chang\Messenger\Socket;

use Chang\Messenger\Message\AbstractPushMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class SocketMiddleware implements MiddlewareInterface
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
     * {@inheritdoc}
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $msg = $message->getMessage();

        if (!$msg instanceof AbstractPushMessage) {
            return $stack->next()->handle($envelope, $stack);
        }

        // ignore add `{$this->key}` extra when bus receive
        if (0 === \count($envelope->all(ReceivedStamp::class))) {
            return $stack->next()->handle($envelope, $stack);
        }

        $msg->addExtra($this->key, [
            'prefix' => $this->prefix,
        ]);

        return $stack->next()->handle($envelope, $stack);
    }
}
