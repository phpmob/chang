<?php

declare(strict_types=1);

namespace Chang\SequenceNumber\EventListener;

use Chang\SequenceNumber\Model\SequenceNumberAwareInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class DoctrineEventSubscriber implements EventSubscriber
{
    /**
     * @var int
     */
    private $latest;

    public function getSubscribedEvents()
    {
        return [
            'prePersist'
        ];
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $object = $event->getObject();

        if (!$object instanceof SequenceNumberAwareInterface) {
            return;
        }

        if (!$this->latest) {
            /** @var SequenceNumberAwareInterface[] $found */
            $found = $event->getObjectManager()
                ->getRepository(get_class($object))
                ->findBy([], ['sequenceNumber' => 'DESC'], 1);

            if (!empty($found)) {
                $this->latest = $found[0]->getSequenceNumber() ?: 1;
            }
        }

        $this->latest++;

        $object->setSequenceNumber($this->latest);
    }
}
