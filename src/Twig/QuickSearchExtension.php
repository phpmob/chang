<?php

declare(strict_types=1);

namespace Chang\Twig;

use Adbar\Dot;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class QuickSearchExtension extends \Twig_Extension
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $securityChecker;

    public function __construct(RequestStack $requestStack, RouterInterface $router, AuthorizationCheckerInterface $securityChecker = null)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->securityChecker = $securityChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('quick_search', [$this, 'set']),
        ];
    }

    public function set(array $searches)
    {
        $request = $this->requestStack->getCurrentRequest();
        $currentIndex = 0;
        $currentRoute = $request->attributes->get('_route');
        $searchItems = [];

        foreach ($searches as $index => $search) {
            if (!empty($search['roles']) && false === $this->isGranted($search['roles'])) {
                continue;
            }

            if (array_key_exists('value', $search) && is_array($search['value'])) {
                $search['value'] = (new Dot($request->get($search['value'][0], [])))->get($search['value'][1]);
            }

            $searchItems[] = array_merge([
                'path' => $this->router->generate($search['route'], $search['parameters']),
            ], $search);

            if ($currentRoute === $search['route']) {
                $currentIndex = $index;
            }
        }

        return [
            'items' => $searchItems,
            'current' => count($searchItems) ? $searchItems[$currentIndex] : null,
        ];
    }

    /**
     * @param $role
     * @param null $object
     *
     * @return bool
     */
    private function isGranted($role, $object = null): bool
    {
        if (null === $this->securityChecker) {
            return false;
        }

        try {
            return $this->securityChecker->isGranted($role, $object);
        } catch (AuthenticationCredentialsNotFoundException $e) {
            return false;
        }
    }
}

