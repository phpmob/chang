<?php

declare(strict_types=1);

namespace Chang\User\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
use Sylius\Component\User\Model\UserInterface;

final class DefaultUsernameSubscriber implements EventSubscriber
{
    /**
     * @var CanonicalizerInterface
     */
    private $canonicalizer;

    public function __construct(CanonicalizerInterface $canonicalizer)
    {
        $this->canonicalizer = $canonicalizer;
    }

    /**
     * @return array
     */
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
    public function prePersist(LifecycleEventArgs $args)
    {
        $user = $args->getObject();

        if (!$user instanceof UserInterface) {
            return;
        }

        if (!$email = $user->getEmail()) {
            return;
        }

        $user->setEmailCanonical($this->canonicalizer->canonicalize($email));

        if ($user->getUsername()) {
            $user->setUsernameCanonical($user->getUsername());

            return;
        }

        $user->setUsername($email);
        $user->setUsernameCanonical($user->getEmailCanonical());
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $user = $args->getObject();

        if (!$user instanceof UserInterface) {
            return;
        }

        if (!$email = $user->getEmail()) {
            return;
        }

        $user->setEmailCanonical($this->canonicalizer->canonicalize($user->getEmail()));
        $user->setUsernameCanonical($this->canonicalizer->canonicalize($user->getUsername()));
    }
}
