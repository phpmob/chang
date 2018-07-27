<?php

declare(strict_types=1);

namespace Chang\Currency;

class Divisor implements DivisorInterface
{
    /**
     * @var int
     */
    private $scale;

    public function __construct(int $scale = 100)
    {
        $this->scale = $scale;
    }

    /**
     * {@inheritdoc}
     */
    public function getScale(): int
    {
        return $this->scale;
    }

    /**
     * {@inheritdoc}
     */
    public function getScaleLength(): int
    {
        return strlen((string)$this->scale);
    }

    /**
     * {@inheritdoc}
     */
    public function getDivided(int $number): float
    {
        return floatval($number / $this->scale);
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiplied(int $number): int
    {
        return (int)round($number * $this->scale);
    }
}
