<?php

declare(strict_types=1);

namespace Chang\Currency;

use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface as BaseMoneyFormatterInterface;

interface MoneyFormatterInterface extends BaseMoneyFormatterInterface
{
    /**
     * @param int $amount
     * @param string|null $currencyCode
     * @param string|null $locale
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function format(int $amount, string $currencyCode = null, ?string $locale = null): string;
}
