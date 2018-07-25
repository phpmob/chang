<?php

declare(strict_types=1);

namespace Chang\Messenger\Manager;

use Chang\Messenger\Model\InboxInterface;
use Sylius\Component\User\Model\UserInterface;

interface InboxManagerInterface
{
    /**
     * @param UserInterface $user
     *
     * @return InboxInterface
     */
    public function findUserInbox(UserInterface $user): InboxInterface;

    /**
     * @param UserInterface $user
     * @param bool $flush
     */
    public function markAsSent(UserInterface $user, bool $flush = true): void;

    /**
     * @param UserInterface $user
     * @param bool $flush
     */
    public function markAsRead(UserInterface $user, bool $flush = true): void;

    /**
     * Flush
     */
    public function flush(): void;
}
