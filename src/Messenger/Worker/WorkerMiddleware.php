<?php

declare(strict_types=1);

namespace Chang\Messenger\Worker;

use Chang\Messenger\Message\AbstractWorkerMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

/**
 * Completely asynchronous without `php/console consume` by using external service (eg. node)
 * to pull message from queue and push back (message) to web uri.
 */
class WorkerMiddleware implements MiddlewareInterface
{
    /**
     * @var HashHandlerInterface
     */
    private $handler;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $key;

    public function __construct(HashHandlerInterface $handler, string $uri, string $key = 'worker')
    {
        $this->handler = $handler;
        $this->uri = $uri;
        $this->key = $key;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $msg = $message->getMessage();

        if (!$msg instanceof AbstractWorkerMessage) {
            return $stack->next()->handle($envelope, $stack);
        }

        // ignore add `{$this->key}` extra when bus receive
        if (\count($envelope->all(ReceivedStamp::class)) && \array_key_exists($this->key, $msg->extras)) {
            // NOTE: You need to manual handle this job separately when receive message via `{$this->key}.uri` action.
            if (false === $this->handler->verify($msg->extras[$this->key])) {
                // break! when invalid verify.
                return null;
            }

            return $stack->next()->handle($envelope, $stack);
        }

        $msg->addExtra($this->key, [
            'uri' => $this->uri,
            'hash' => $hash = md5(uniqid($this->key)),
            'signature' => $this->handler->encode($hash),
        ]);

        $this->handler->store($hash);

        return $stack->next()->handle($envelope, $stack);
    }
}
