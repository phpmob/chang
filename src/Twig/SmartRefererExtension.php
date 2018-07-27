<?php

declare(strict_types=1);

namespace Chang\Twig;

use Symfony\Component\HttpFoundation\RequestStack;

class SmartRefererExtension extends \Twig_Extension
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('smart_referer', [$this, 'getReferer']),
        ];
    }

    /**
     * @param null $default
     *
     * @return null|string
     */
    public function getReferer($default = null)
    {
        if (!$current = $this->requestStack->getCurrentRequest()) {
            return $default;
        }

        $referer = $current->headers->get('referer');

        if ($referer === $current->getUri()) {
            return $default;
        }

        return $referer;
    }
}
