<?php

declare(strict_types=1);

namespace Chang\Messenger\Message;

use Chang\Messenger\Model\MessageInterface;

abstract class AbstractMessage implements \JsonSerializable
{
    /**
     * @var \Closure|array
     */
    public $body;

    /**
     * @var array
     */
    public $extras = [];

    /**
     * @var bool
     */
    private $expired = false;

    /**
     * @var MessageInterface
     */
    private $rawMessage;

    public function __construct($body = null)
    {
        $this->body = $body;
    }

    /**
     * @return MessageInterface
     */
    public function getRawMessage(): MessageInterface
    {
        return $this->rawMessage;
    }

    /**
     * @param MessageInterface $rawMessage
     */
    public function setRawMessage(MessageInterface $rawMessage): void
    {
        $this->rawMessage = $rawMessage;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expired ? $this->expired : (
            $this->rawMessage ? $this->rawMessage->isExpired() : false
        );
    }

    /**
     * @param bool $expired
     */
    public function setExpired(bool $expired): void
    {
        $this->expired = $expired;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'body' => \is_callable($this->body) ? \call_user_func($this->body) : $this->body,
            'extras' => $this->extras,
        ];
    }

    /**
     * @param string $key
     * @param $value
     *
     * @return AbstractMessage
     */
    public function addExtra(string $key, $value): self
    {
        $this->extras[$key] = $value;

        return $this;
    }

    /**
     * @param MessageInterface $rawMessage
     *
     * @return self
     */
    public static function create(MessageInterface $rawMessage): self
    {
        $message = new static(function () use ($rawMessage) {
            return $rawMessage->getBody();
        });

        $message->setRawMessage($rawMessage);

        return $message;
    }
}
