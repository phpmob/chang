<?php

declare(strict_types=1);

namespace Chang\Menu;

use Knp\Menu\Provider\MenuProviderInterface;

class MenuProvider implements MenuProviderInterface
{
    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @var array
     */
    private $menus = [];

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, array $options = array())
    {
        return $this->builder->get($name, $this->menus);
    }

    /**
     * {@inheritdoc}
     */
    public function has($name, array $options = array())
    {
        if (!isset($this->menus[$name])) {
            $this->menus[$name] = array_replace_recursive($this->menus[$name] ?? [], $options);
        }

        return true;
    }
}
