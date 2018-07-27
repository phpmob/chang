<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

class SenderRegistry implements SenderRegistryInterface
{
    /**
     * @var SenderInterface[]
     */
    private $senders;

    /**
     * {@inheritdoc}
     */
    public function add(string $key, SenderInterface $sender): void
    {
        $this->senders[$key] = $sender;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): SenderInterface
    {
        if (!array_key_exists($key, $this->senders)) {
            throw new SenderException("Push sender named $key not available.", 500);
        }

        return $this->senders[$key];
    }
}
