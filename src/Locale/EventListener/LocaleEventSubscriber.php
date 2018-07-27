<?php

declare(strict_types=1);

namespace Chang\Locale\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Exception\DeleteHandlingException;

class LocaleEventSubscriber implements EventSubscriber
{
    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    public function __construct(LocaleContextInterface $localeContext)
    {
        $this->localeContext = $localeContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            'preRemove',
        ];
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     *
     * @return bool
     */
    private function isSupported(LifecycleEventArgs $eventArgs)
    {
        return $eventArgs->getObject() instanceof LocaleInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        if (!$this->isSupported($eventArgs)) {
            return;
        }

        if (strtolower($this->localeContext->getLocaleCode()) === strtolower($eventArgs->getObject()->getCode())) {
            throw new DeleteHandlingException(
                'Ups, something went wrong during deleting a resource, please try again.',
                'can_not_delete_default_locale'
            );
        }
    }
}
