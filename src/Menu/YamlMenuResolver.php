<?php

declare(strict_types=1);

namespace Chang\Menu;

use Chang\Context\Page\PageContextInterface;

class YamlMenuResolver implements YamlMenuResolverInterface
{
    /**
     * @var PageContextInterface
     */
    private $context;

    public function __construct(PageContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getMenus(): array
    {
        return $this->context->get('menus', []);
    }
}
