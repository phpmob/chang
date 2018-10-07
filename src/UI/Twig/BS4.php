<?php

declare(strict_types=1);

namespace Chang\UI\Twig;

use Chang\UI\HtmlAttrs;

class BS4 extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('ui_tabs', [$this, 'getTabs'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ui_pills', [$this, 'getPills'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ui_tab', [$this, 'getTab'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param array $option
     *
     * @return string
     */
    public function getTab(array $option = []): string
    {
        $attrs = array_replace_recursive([
            'role' => 'tab',
            'class' => 'nav-link',
            'data-toggle' => 'tab',
            'href' => $option['href'] ?? '#',
        ], $option['attr'] ?? []);

        if (isset($option['active']) && !empty($option['active'])) {
            $attrs['class'] .= ' active';
        }

        $attrs = HtmlAttrs::build($attrs);

        return vsprintf('<li class="nav-item"><a %s>%s%s</a></li>', [
            $attrs,
            isset($option['icon']) ? '<i class="' . $option['icon'] . '"></i>' : '',
            $option['text'] ?? ''
        ]);
    }

    /**
     * @param array $items
     * @param array $attrs
     *
     * @return string
     */
    public function getTabs(array $items, array $attrs = []): string
    {
        $attrs = HtmlAttrs::build(array_replace_recursive([
            'role' => 'tablist',
            'class' => 'nav nav-tabs',
        ], $attrs));

        return vsprintf('<ul %s>%s</ul>', [
            $attrs,
            implode('', array_map([$this, 'getTab'], $items)),
        ]);
    }

    /**
     * @param array $items
     * @param array $attrs
     *
     * @return string
     */
    public function getPills(array $items, array $attrs = []): string
    {
        return $this->getTabs($items, array_replace_recursive([
            'class' => 'nav nav-pills',
        ], $attrs));
    }
}
