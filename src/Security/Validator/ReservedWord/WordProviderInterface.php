<?php

declare(strict_types=1);

namespace Chang\Security\Validator\ReservedWord;

interface WordProviderInterface
{
    /**
     * @param $text
     *
     * @return bool
     */
    public function match($text): bool;
}
