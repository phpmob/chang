<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

interface MessageRecipientInterface
{
    /**
     * @return null|string
     */
    public function getRecipientHash(): ?string;

    /**
     * @param null|string $hash
     */
    public function setRecipientHash(?string $hash): void;
}
