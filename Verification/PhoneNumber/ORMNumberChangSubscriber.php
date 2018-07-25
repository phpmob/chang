<?php

declare(strict_types=1);

namespace Chang\Verification\PhoneNumber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class ORMNumberChangSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            'preUpdate'
        ];
    }

    /**
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof NumberAwareInterface) {
            return;
        }

        if ($event->hasChangedField('phoneNumber')) {
            // force user to being verify process.
            $object->setPhoneNumberVerifiedAt(null);

            // username as phoneNumber if necessary.
            if (!$object->getUsername() || $object->getUsername() === $event->getOldValue('phoneNumber')) {
                $object->setUsername($object->getPhoneNumber());
                $object->setUsernameCanonical($object->getPhoneNumber());
            }
        }
    }
}
