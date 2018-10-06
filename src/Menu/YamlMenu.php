<?php

declare(strict_types=1);

namespace Chang\Menu;

use Adbar\Dot;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class YamlMenu implements EventSubscriberInterface
{
    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @var YamlMenuResolverInterface
     */
    private $resolver;

    public function __construct(BuilderInterface $builder, YamlMenuResolverInterface $resolver)
    {
        $this->builder = $builder;
        $this->resolver = $resolver;
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
        $menus = new Dot($this->resolver->getMenus());

        if ($items = $menus->get($event->getChildPathKey())) {
            $this->builder->buildChild($event->getMenu(), $items);
        }
    }
}
