<?php

declare(strict_types=1);

namespace Chang\Verification\PhoneNumber;

interface TokenSenderInterface
{
    /**
     * @param NumberAwareInterface $numberAware
     */
    public function send(NumberAwareInterface $numberAware): void;
}
