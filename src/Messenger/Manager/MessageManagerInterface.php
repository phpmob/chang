<?php

declare(strict_types=1);

namespace Chang\Messenger\Manager;

use Chang\Messenger\Model\MessageInterface;
use Chang\Messenger\Model\MessageRecipientInterface;
use Chang\Messenger\Model\RecipientInterface;
use Chang\Messenger\Message\AbstractMessage;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\UserInterface;

interface MessageManagerInterface
{
    /**
     * @param string $type
     * @param string $title
     *
     * @return MessageInterface
     */
    public function createMessage(string $type, string $title): MessageInterface;

    /**
     * @param MessageRecipientInterface $recipient
     * @param string $type
     * @param string $title
     *
     * @return MessageInterface
     */
    public function createPrivateMessage(MessageRecipientInterface $recipient, string $type, string $title): MessageInterface;

    /**
     * @param string $type
     * @param string $title
     *
     * @return MessageInterface
     */
    public function createGlobalMessage(string $type, string $title): MessageInterface;

    /**
     * @param MessageRecipientInterface $recipient
     *
     * @return RecipientInterface
     */
    public function createRecipient(MessageRecipientInterface $recipient): RecipientInterface;

    /**
     * @param AbstractMessage $message
     */
    public function send(AbstractMessage $message): void;

    /**
     * @param MessageInterface|ResourceInterface $message
     * @param UserInterface $user
     */
    public function markAsRead(MessageInterface $message, UserInterface $user): void;

    /**
     * @param MessageInterface $message
     * @param UserInterface $user
     */
    public function markAsUnread(MessageInterface $message, UserInterface $user): void;

    /**
     * @param UserInterface $user
     */
    public function markAllAsRead(UserInterface $user): void;
}
