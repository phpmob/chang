<?php

declare(strict_types=1);

namespace Chang\Messenger\Worker;

interface HashHandlerInterface
{
    /**
     * @param string $hash
     * @param bool $flush
     */
    public function store(string $hash, bool $flush = true): void;

    /**
     * @param array $message
     * @param bool $flush
     *
     * @return bool
     */
    public function verify(array $message, bool $flush = true): bool;

    /**
     * @param string $hash
     *
     * @return string
     */
    public function encode(string $hash): string;

    /**
     * Flush hash
     */
    public function flush(): void;
}
