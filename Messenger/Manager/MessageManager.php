<?php

declare(strict_types=1);

namespace Chang\Messenger\Manager;

use Chang\Messenger\Model\MessageInterface;
use Chang\Messenger\Model\MessageRecipientInterface;
use Chang\Messenger\Model\RecipientInterface;
use Chang\Messenger\Message\AbstractMessage;
use Chang\Messenger\Model\ViewerInterface;
use Chang\Messenger\Queue\InboxMessage;
use Chang\Messenger\Repository\MessageRepositoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageManager implements MessageManagerInterface
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @var FactoryInterface
     */
    private $messageFactory;

    /**
     * @var FactoryInterface
     */
    private $recipientFactory;

    /**
     * @var ObjectManager
     */
    private $messageManager;

    /**
     * @var MessageRepositoryInterface
     */
    private $messageRepository;

    /**
     * @var FactoryInterface
     */
    private $viewerFactory;

    /**
     * @var InboxManagerInterface
     */
    private $inboxManager;

    public function __construct(
        MessageBusInterface $bus,
        ObjectManager $messageManager,
        MessageRepositoryInterface $messageRepository,
        FactoryInterface $messageFactory,
        FactoryInterface $recipientFactory,
        FactoryInterface $viewerFactory,
        InboxManagerInterface $inboxManager
    )
    {
        $this->bus = $bus;
        $this->messageManager = $messageManager;
        $this->messageRepository = $messageRepository;
        $this->messageFactory = $messageFactory;
        $this->recipientFactory = $recipientFactory;
        $this->viewerFactory = $viewerFactory;
        $this->inboxManager = $inboxManager;
    }

    /**
     * {@inheritdoc}
     */
    public function createMessage(string $type, string $title): MessageInterface
    {
        /** @var MessageInterface $object */
        $object = $this->messageFactory->createNew();
        $object->setType($type);
        $object->setTitle($title);

        $this->messageManager->persist($object);

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function createPrivateMessage(MessageRecipientInterface $recipient, string $type, string $title): MessageInterface
    {
        $message = $this->createMessage($title, $title);
        $message->setGlobal(false);
        $message->addRecipient($this->createRecipient($recipient));

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function createGlobalMessage(string $type, string $title): MessageInterface
    {
        $message = $this->createMessage($title, $title);
        $message->setGlobal(true);

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function createRecipient(MessageRecipientInterface $recipient): RecipientInterface
    {
        if (!$recipient->getRecipientHash()) {
            throw new \LogicException(sprintf('Have no recipient hash for "%s".', (string)$recipient));
        }

        /** @var RecipientInterface $object */
        $object = $this->recipientFactory->createNew();
        $object->setUser($recipient);

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function send(AbstractMessage $message): void
    {
        $this->messageManager->flush();
        $this->bus->dispatch($message);

        foreach ($message->getRawMessage()->getRecipients() as $recipient) {
            $this->inboxManager->markAsSent($recipient->getUser(), false);
        }

        $this->inboxManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function markAsRead(MessageInterface $message, UserInterface $user): void
    {
        /** @var ViewerInterface $viewer */
        $viewer = $this->viewerFactory->createNew();
        $viewer->setUser($user);
        $message->addViewer($viewer);
        $this->messageManager->flush();

        $this->inboxManager->markAsRead($user);
    }

    /**
     * {@inheritdoc}
     */
    public function markAsUnread(MessageInterface $message, UserInterface $user): void
    {
        $message->removeViewerByUser($user);
        $this->messageManager->flush();

        $this->inboxManager->markAsRead($user);
    }

    /**
     * {@inheritdoc}
     */
    public function markAllAsRead(UserInterface $user): void
    {
        /** @var ViewerInterface $viewer */
        $viewer = $this->viewerFactory->createNew();
        $viewer->setUser($user);

        $max = 20;
        $messages = $this->messageRepository
            ->createUnseenUserPaginator($user)
            ->setMaxPerPage($max);

        /** @var MessageInterface $message */
        foreach ($messages as $message) {
            $message->addViewer(clone $viewer);
        }

        $count = $messages->count();
        $this->messageManager->flush();

        $this->inboxManager->markAsRead($user);

        if ($count > $max) {
            $this->bus->dispatch(new InboxMessage(['user' => (string)$user]));
        }
    }
}
