<?php

declare(strict_types=1);

namespace Chang\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

class MenuEvent extends Event
{
    const NAME = 'chang.menu';

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var ItemInterface
     */
    private $menu;

    public function __construct(FactoryInterface $factory, ItemInterface $menu)
    {
        $this->factory = $factory;
        $this->menu = $menu;
    }

    /**
     * @return FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return ItemInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        $obj = $this->menu;
        $paths = [];

        do {
            $paths[] = $obj->getName();
        } while ($obj = $obj->getParent());

        return array_reverse($paths);
    }

    /**
     * @return string
     */
    public function getPathKey(): string
    {
        return implode('.', $this->getPaths());
    }

    /**
     * @return string
     */
    public function getChildPathKey(): string
    {
        return implode('.', $this->getPaths()) . ($this->menu->getParent() ? '.child' : '');
    }
}
