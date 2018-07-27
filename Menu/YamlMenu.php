<?php

declare(strict_types=1);

namespace Chang\Menu;

use Adbar\Dot;
use Chang\Context\Page\PageContextInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class YamlMenu implements EventSubscriberInterface
{
    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @var PageContextInterface
     */
    private $context;

    public function __construct(BuilderInterface $builder, PageContextInterface $context)
    {
        $this->builder = $builder;
        $this->context = $context;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            MenuEvent::NAME => 'build'
        ];
    }

    /**
     * @param MenuEvent $event
     */
    public function build(MenuEvent $event): void
    {
        $menus = new Dot($this->context->get('menus', []));

        if ($items = $menus->get($event->getChildPathKey())) {
            $this->builder->buildChild($event->getMenu(), $items);
        }
    }
}
