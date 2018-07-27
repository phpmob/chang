<?php

declare(strict_types=1);

namespace Chang\Messenger\Worker;

use Chang\Messenger\Model\HashStorageInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class HashHandler implements HashHandlerInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var string
     */
    private $secret;

    public function __construct(ObjectManager $manager, RepositoryInterface $repository, string $secret)
    {
        $this->manager = $manager;
        $this->repository = $repository;
        $this->secret = $secret;
    }

    /**
     * {@inheritdoc}
     */
    public function store(string $hash, bool $flush = true): void
    {
        /** @var HashStorageInterface $object */
        $object = $this->repository->getClassName();
        $object = new $object;
        $object->setId($hash);

        $this->manager->persist($object);

        if ($flush) {
            $this->manager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function verify(array $message, bool $flush = true): bool
    {
        $hash = $message['hash'] ?? null;
        $signature = $message['signature'] ?? null;

        // continue to next when no push-aware
        if (null === $signature || null === $hash) {
            return false;
        }

        /** @var HashStorageInterface $object */
        $object = $this->repository->find($hash);

        // stop handling when hash not found
        if (null === $object) {
            return false;
        }

        $this->manager->remove($object);

        if ($flush) {
            $this->manager->flush();
        }
        // stop handling when hash not valid
        if ($signature !== $this->encode((string)$hash)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
        $this->manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function encode(string $hash): string
    {
        return base64_encode(hash_hmac('sha256', $hash, $this->secret, true));
    }
}
