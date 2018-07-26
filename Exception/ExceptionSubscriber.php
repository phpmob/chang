<?php

declare(strict_types=1);

namespace Chang\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array(
                array('onKernelException', 999),
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!$exception instanceof HttpRedirectException) {
            return; //throw $exception;
        }

        $event->allowCustomResponseCode();
        $event->setResponse(RedirectResponse::create($exception->getTargetUrl(), $exception->getStatus()));
    }
}
