<?php

declare(strict_types=1);

namespace Chang\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ArrayBuilder implements BuilderInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var \Closure[]
     */
    private $parsers = [];

    public function __construct(
        FactoryInterface $factory,
        AuthorizationCheckerInterface $checker,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->factory = $factory;
        $this->checker = $checker;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, array $options = []): ?ItemInterface
    {
        $menu = $this->parseOptions($options[$name]);
        $root = $this->factory->createItem($name, $menu['options']);

        $this->dispatcher->dispatch(MenuEvent::NAME, new MenuEvent($this->factory, $root));

        if (!$this->canAccess($menu['options'])) {
            return null;
        }

        $this->buildChild($root, $menu['child']);

        return $root;
    }

    /**
     * @param ItemInterface $item
     * @param array $children
     */
    public function buildChild(ItemInterface $item, array $children): void
    {
        foreach ($children as $name => $child) {
            if (!$this->canAccess($child)) {
                continue;
            }

            $menu = $this->parseOptions($child);
            $childItem = $item->addChild($name, $menu['options']);

            $this->dispatcher->dispatch(MenuEvent::NAME, new MenuEvent($this->factory, $childItem));

            if (isset($menu['child'])) {
                $this->buildChild($childItem, $menu['child']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addOptionParser(\Closure $closure): void
    {
        $this->parsers[] = $closure;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function parseOptions(array $options): array
    {
        $child = $options['child'] ?? [];

        if (isset($options['header'])) {
            $options['extras'] = $options['extras'] ?? [];
            $options['extras'] = array_merge($options['extras'], ['header' => $options['header']]);
        }

        if (isset($options['divider'])) {
            $options['extras'] = $options['extras'] ?? [];
            $options['extras'] = array_merge($options['extras'], ['divider' => true]);
        }

        if (isset($options['icon'])) {
            $options['extras'] = $options['extras'] ?? [];
            $options['extras'] = array_merge($options['extras'], ['icon' => $options['icon']]);
        }

        if (isset($options['route']) && is_array($options['route'])) {
            $options['routeParameters'] = $options['route']['[parameters'] ?? [];
            $options['route'] = $options['route']['name'];
        }

        foreach ($this->parsers as $parser) {
            if ($result = $parser($options)) {
                $options = $result;
            }
        }

        unset($options['child']);

        return [
            'options' => $options,
            'child' => $child,
        ];
    }

    /**
     * @param array $menu
     *
     * @return bool
     */
    private function canAccess(array $menu): bool
    {
        if (isset($menu['role']) && !$this->checker->isGranted((array)$menu['role'])) {
            return false;
        }

        return true;
    }
}
