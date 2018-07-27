<?php

declare(strict_types=1);

namespace Chang\Menu;

use Knp\Menu\ItemInterface;

interface BuilderInterface
{
    /**
     * @param $name
     * @param array $options
     *
     * @return ItemInterface|null
     */
    public function get($name, array $options = []): ?ItemInterface;

    /**
     * @param ItemInterface $item
     * @param array $children
     */
    public function buildChild(ItemInterface $item, array $children): void;

    /**
     * @param \Closure $closure
     */
    public function addOptionParser(\Closure $closure): void;
}
