<?php

declare(strict_types=1);

namespace Chang\Application;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var OptionResolver
     */
    private $resolver;

    public function __construct(OptionResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('chang_option', [$this->resolver, 'get']),
        ];
    }
}
