<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

use Sylius\Component\User\Model\UserInterface;

abstract class Recipient implements RecipientInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var MessageInterface|null
     */
    protected $message;

    /**
     * @var UserInterface|null
     */
    protected $user;

    /**
     * @var \DateTime|null
     */
    protected $sentAt;

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
    public function getMessage(): ?MessageInterface
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage(?MessageInterface $message): void
    {
        $this->message = $message;
    }

    /**
     * @return null|UserInterface
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
    public function getSentAt(): ?\DateTime
    {
        return $this->sentAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setSentAt(?\DateTime $sentAt): void
    {
        $this->sentAt = $sentAt;
    }
}
