<?php

declare(strict_types=1);

namespace Chang\User\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
use Sylius\Component\User\Model\UserInterface;

final class DefaultUsernameORMSubscriber implements EventSubscriber
{
    /**
     * @var CanonicalizerInterface
     */
    private $canonicalizer;

    /**
     * @param CanonicalizerInterface $canonicalizer
     */
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

        $user->setEmailCanonical($this->canonicalizer->canonicalize($user->getEmail()));

        if ($user->getUsername()) {
            $user->setUsernameCanonical($user->getUsername());

            return;
        }

        $user->setUsername($user->getEmail());
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

        $user->setEmailCanonical($this->canonicalizer->canonicalize($user->getEmail()));
        $user->setUsernameCanonical($this->canonicalizer->canonicalize($user->getUsername()));
    }
}
