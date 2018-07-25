<?php

declare(strict_types=1);

namespace Chang\Messenger\Queue;

use Chang\Messenger\Manager\MessageManagerInterface;
use Sylius\Bundle\UserBundle\Provider\UserProviderInterface;

class InboxMessageHandler
{
    /**
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    public function __construct(MessageManagerInterface $messageManager, UserProviderInterface $userProvider)
    {
        $this->messageManager = $messageManager;
        $this->userProvider = $userProvider;
    }

    public function __invoke(InboxMessage $message)
    {
        $this->messageManager->markAllAsRead(
            $this->userProvider->loadUserByUsername($message->body['user'])
        );
    }
}
