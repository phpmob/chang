<?php

declare(strict_types=1);

namespace Chang\SequenceNumber\Model;

trait SequenceNumberTrait
{
    /**
     * @var int
     */
    protected $sequenceNumber = 0;

    /**
     * @return int
     */
    public function getSequenceNumber(): int
    {
        return intval($this->sequenceNumber);
    }

    /**
     * @param int|null $sequenceNumber
     */
    public function setSequenceNumber(?int $sequenceNumber): void
    {
        $this->sequenceNumber = intval($sequenceNumber);
    }
}
