<?php

declare(strict_types=1);

namespace Chang\Twig;

class PrintIfExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('print_if', [$this, 'printIf']),
        ];
    }

    /**
     * @param string $text
     * @param boolean $condition
     *
     * @return string
     */
    public function printIf($text, $condition)
    {
        return $condition ? $text : '';
    }
}
