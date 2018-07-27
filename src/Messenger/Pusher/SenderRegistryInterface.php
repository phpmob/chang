<?php

declare(strict_types=1);

namespace Chang\Messenger\Pusher;

interface SenderRegistryInterface
{
    /**
     * @param string $key
     * @param SenderInterface $sender
     */
    public function add(string $key, SenderInterface $sender): void;

    /**
     * @param string $key
     *
     * @return SenderInterface
     *
     * @throws SenderException
     */
    public function get(string $key): SenderInterface;
}
