<?php

declare(strict_types=1);

namespace Chang\Menu;

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
        $section = $event->getMenu()->getName();
        $menus = $this->context->get('menus', []);

        if (array_key_exists($section, $menus)) {
            $this->builder->buildChild($event->getMenu(), $menus[$section]);
        }
    }
}
