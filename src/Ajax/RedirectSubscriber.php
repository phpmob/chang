<?php

declare(strict_types=1);

namespace Chang\Ajax;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RedirectSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array(
                array('onKernelResponse'),
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();
        $noFollow = !$request->get('_follow_redirect', false);

        if ($request->isXmlHttpRequest() && $noFollow && $response instanceof RedirectResponse) {
            // prefix `x-` to prevent cloudflare redirect.
            $data = ['x-chang-location' => $response->getTargetUrl()];
            $isJson = 'json' === $request->getRequestFormat()
                || 'json' === $request->getFormat($request->headers->get('content-type'));

            $event->setResponse($isJson
                ? JsonResponse::create($data, Response::HTTP_OK, $data)
                : Response::create(null, Response::HTTP_OK, $data)
            );
        }
    }
}
