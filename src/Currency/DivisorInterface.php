<?php

declare(strict_types=1);

namespace Chang\Currency;

interface DivisorInterface
{
    /**
     * @return int
     */
    public function getScale(): int;

    /**
     * @return int
     */
    public function getScaleLength(): int;

    /**
     * @param int $number
     *
     * @return float
     */
    public function getDivided(int $number): float;

    /**
     * @param int $number
     *
     * @return int
     */
    public function getMultiplied(int $number): int;
}
