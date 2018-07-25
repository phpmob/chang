<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\User\Model\UserAwareInterface;

interface InboxInterface extends ResourceInterface, UserAwareInterface, TimestampableInterface, \JsonSerializable
{
    /**
     * @return int
     */
    public function getTotal(): int;

    /**
     * @param int $total
     */
    public function setTotal(int $total): void;

    /**
     * @return int
     */
    public function getTotalSeen(): int;

    /**
     * @param int $totalSeen
     */
    public function setTotalSeen(int $totalSeen): void;

    /**
     * @return int
     */
    public function getTotalUnseen(): int;
}
