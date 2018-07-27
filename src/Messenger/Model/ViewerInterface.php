<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\UserAwareInterface;

interface ViewerInterface extends ResourceInterface, UserAwareInterface
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
    public function getSeenAt(): ?\DateTime;

    /**
     * @param \DateTime|null $seenAt
     */
    public function setSeenAt(?\DateTime $seenAt): void;
}
