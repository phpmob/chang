<?php

declare(strict_types=1);

namespace Chang\SecureArea;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Templating\EngineInterface;

class AccessListener implements EventSubscriberInterface
{
    const SESSION_KEY = '_chang_secure_area_lifetime';

    /**
     * @var UserPasswordCheckerInterface
     */
    private $checker;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var array
     */
    private $options;

    public function __construct(
        UserPasswordCheckerInterface $checker,
        EngineInterface $templating,
        SessionInterface $session = null,
        array $options = [])
    {
        $this->checker = $checker;
        $this->templating = $templating;
        $this->session = $session;

        $this->configure($options);
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
        if (!$this->session || !$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->checker->getUser() || !$this->isMatched($request)) {
            return;
        }

        if (!$this->isExpired()) {
            return;
        }

        if ('POST' === $request->getRealMethod()) {
            $password = $request->request->get($this->options['password_parameter']);

            if (!$this->checker->isValid($password)) {
                $this->createResponse($event, ['last_error' => [
                    'messageKey' => 'Invalid credentials.',
                    'messageData' => [],
                ]]);

                return;
            }

            // store last session lifetime
            $this->session->set(self::SESSION_KEY, time());

            // redirect to current with GET request.
            $event->stopPropagation();
            $event->setResponse(RedirectResponse::create($request->getRequestUri()));

            return;
        }

        $this->createResponse($event);
    }

    private function createResponse(GetResponseEvent $event, array $parameters = []): void
    {
        $event->stopPropagation();
        $event->setResponse(Response::create(
            $this->templating->render($this->options['template'], array_merge($parameters, [
                'password_parameter' => $this->options['password_parameter'],
            ]))
        ));
    }

    private function isMatched(Request $request): bool
    {
        /** @var array|string $pattern */
        foreach ($this->options['patterns'] as $pattern) {
            if ((new RequestMatcher(
                $pattern['path'] ?? (is_string($pattern) ? $pattern : null),
                $pattern['host'] ?? null,
                $pattern['methods'] ?? null,
                $pattern['ips'] ?? null,
                $pattern['attributes'] ?? [],
                $pattern['schemes'] ?? null
            ))->matches($request)) {
                return true;
            };
        }

        return false;
    }

    private function isExpired(): bool
    {
        if (!$lasttime = $this->session->get(self::SESSION_KEY)) {
            return true;
        }

        $lifetime = (new \DateTime())
            ->setTimestamp($lasttime)
            ->add(\DateInterval::createFromDateString($this->options['lifetime']));

        return new \DateTime() > $lifetime;
    }

    private function configure(array $options): void
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'password_parameter' => '_password',
            'lifetime' => '+15 minutes',
            'patterns' => [],
        ]);

        $resolver->setRequired(['template']);
        $resolver->setAllowedTypes('lifetime', ['string']);
        $resolver->setAllowedTypes('patterns', ['array']);

        $this->options = $resolver->resolve($options);
    }
}
