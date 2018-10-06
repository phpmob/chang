<?php

declare(strict_types=1);

namespace Chang\Menu;

interface YamlMenuResolverInterface
{
    /**
     * @return array
     */
    public function getMenus(): array;
}
