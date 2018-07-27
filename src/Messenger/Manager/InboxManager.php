<?php

declare(strict_types=1);

namespace Chang\Messenger\Manager;

use Chang\Messenger\Model\InboxInterface;
use Chang\Messenger\Repository\MessageRepositoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface;

class InboxManager implements InboxManagerInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var MessageRepositoryInterface
     */
    private $messageRepository;

    public function __construct(ObjectManager $manager, RepositoryInterface $repository, MessageRepositoryInterface $messageRepository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param UserInterface $user
     *
     * @return InboxInterface
     */
    public function findUserInbox(UserInterface $user): InboxInterface
    {
        /** @var InboxInterface $inbox */
        if (!$inbox = $this->repository->findOneBy(['user' => $user])) {
            $class = $this->repository->getClassName();
            $inbox = new $class();
            $inbox->setUser($user);
            $this->repository->add($inbox);
        }

        return $inbox;
    }

    /**
     * {@inheritdoc}
     */
    public function markAsSent(UserInterface $user, bool $flush = true): void
    {
        $this->findUserInbox($user)->setTotal(
            $this->messageRepository->getUserTotal($user)
        );

        if ($flush) {
            $this->manager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function markAsRead(UserInterface $user, bool $flush = true): void
    {
        $this->findUserInbox($user)->setTotalSeen(
            $this->messageRepository->getUserTotalSeen($user)
        );

        if ($flush) {
            $this->manager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
        $this->manager->flush();
    }
}
