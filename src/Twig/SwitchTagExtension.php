<?php

declare(strict_types=1);

namespace Chang\Twig;

use Chang\Twig\Tag\SwitchTokenParser;

class SwitchTagExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [
            new SwitchTokenParser(),
        ];
    }
}
