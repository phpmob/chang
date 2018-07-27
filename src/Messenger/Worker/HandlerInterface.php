<?php

declare(strict_types=1);

namespace Chang\Messenger\Worker;

interface HandlerInterface
{
    /**
     * Return supported message classes.
     *
     * @return array
     */
    public static function getHandledMessages(): array;
}
