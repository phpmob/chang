<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\UserAwareInterface;
use Sylius\Component\User\Model\UserInterface;

interface RecipientInterface extends ResourceInterface, UserAwareInterface
{
    /**
     * @return MessageInterface|null
     */
    public function getMessage(): ?MessageInterface;

    /**
     * @param MessageInterface|null $message
     */
    public function setMessage(?MessageInterface $message): void;

    /**
     * @return \DateTime|null
     */
    public function getSentAt(): ?\DateTime;

    /**
     * @param \DateTime|null $sentAt
     */
    public function setSentAt(?\DateTime $sentAt): void;

    /**
     * @return null|UserInterface|MessageRecipientInterface
     */
    public function getUser(): ?UserInterface;

    /**
     * @param null|UserInterface|MessageRecipientInterface $user
     */
    public function setUser(? UserInterface $user): void;
}
