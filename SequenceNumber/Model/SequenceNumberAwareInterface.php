<?php

declare(strict_types=1);

namespace Chang\SequenceNumber\Model;

interface SequenceNumberAwareInterface
{
    /**
     * @param int|null $number
     */
    public function setSequenceNumber(?int $number): void;

    /**
     * @return int
     */
    public function getSequenceNumber(): int;
}
