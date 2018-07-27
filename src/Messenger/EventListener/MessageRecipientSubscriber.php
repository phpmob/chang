<?php

declare(strict_types=1);

namespace Chang\Messenger\EventListener;

use Chang\Messenger\Model\MessageRecipientInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Ramsey\Uuid\Uuid;

class MessageRecipientSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->preUpdate($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if (!$object instanceof MessageRecipientInterface) {
            return;
        }

        $object->setRecipientHash(Uuid::uuid4()->toString());
    }
}
