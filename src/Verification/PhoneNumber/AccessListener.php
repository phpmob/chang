<?php

declare(strict_types=1);

namespace Chang\Verification\PhoneNumber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccessListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $tokenStorage;

    /**
     * @var string
     */
    private $targetUrl;

    /**
     * @var array
     */
    private $ignoredRequest;

    public function __construct(TokenStorageInterface $tokenStorage, string $targetUrl, array $ignoredRequest = [])
    {
        $this->tokenStorage = $tokenStorage;
        $this->targetUrl = $targetUrl;
        $this->ignoredRequest = $ignoredRequest;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(
                // wait for token
                array('onKernelRequest', -999),
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!$token->getUser() instanceof NumberAwareInterface) {
            return;
        }

        if (!$token->getUser()->isPhoneNumberVerified()) {
            foreach ($this->ignoredRequest as $pattern) {
                if ((new RequestMatcher(
                    $pattern['path'] ?? (is_string($pattern) ? $pattern : null),
                    $pattern['host'] ?? null,
                    $pattern['methods'] ?? null,
                    $pattern['ips'] ?? null,
                    $pattern['attributes'] ?? [],
                    $pattern['schemes'] ?? null
                ))->matches($event->getRequest())) {
                    return;
                };
            }

            $event->setResponse(RedirectResponse::create($this->targetUrl));
            $event->stopPropagation();
        }
    }
}
