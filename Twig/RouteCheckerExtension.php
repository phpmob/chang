<?php

declare(strict_types=1);

namespace Chang\Twig;

use Symfony\Component\HttpFoundation\RequestStack;

final class RouteCheckerExtension extends \Twig_Extension
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
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
            new \Twig_SimpleFunction('is_route_eq', [$this, 'isRouteName']),
        ];
    }

    /**
     * @param string $name #Route
     *
     * @return bool
     */
    public function isRouteName($name)
    {
        if (!$request = $this->requestStack->getMasterRequest()) {
            return false;
        }

        return $name === $request->get('_route');
    }
}
