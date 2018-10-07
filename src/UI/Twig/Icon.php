<?php

declare(strict_types=1);

namespace Chang\UI\Twig;

class Icon extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('ui_mt_icon', [$this, 'getMaterialIcon'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ui_sl_icon', [$this, 'getSimpleLineIcon'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ui_icon', [$this, 'getIcon'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getIcon(string $key): string
    {
        if (preg_match('/^:/', $key)) {
            return $this->getMaterialIcon(str_replace(':', '', $key));
        }

        return sprintf('<i class="%s"></i>', $key);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getSimpleLineIcon(string $key): string
    {
        return sprintf('<i class="sl-ico-%s"></i>', $key);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getMaterialIcon(string $key): string
    {
        return sprintf('<i class="material-icons">%s</i>', $key);
    }
}
