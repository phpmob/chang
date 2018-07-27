<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\User\Model\UserInterface;

abstract class Inbox implements InboxInterface
{
    use TimestampableTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $total = 0;

    /**
     * @var int
     */
    private $totalSeen = 0;

    /**
     * @var UserInterface|MessageRecipientInterface
     */
    protected $user;

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalSeen(): int
    {
        return $this->totalSeen;
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalSeen(int $totalSeen): void
    {
        $this->totalSeen = $totalSeen;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalUnseen(): int
    {
        return $this->total - $this->totalSeen;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'total' => $this->total,
            'total_seen' => $this->totalSeen,
            'total_unseen' => $this->getTotalUnseen(),
            'created_at' => ($this->createdAt ?? new \DateTime())->getTimestamp(),
            'updated_at' => ($this->updatedAt ?? new \DateTime())->getTimestamp(),
            'recipient' => $this->user->getRecipientHash(),
        ];
    }
}
