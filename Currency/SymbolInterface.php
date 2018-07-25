<?php

declare(strict_types=1);

namespace Chang\Currency;

interface SymbolInterface
{
    /**
     * @param string $currency
     * @param null|string $locale
     *
     * @return \NumberFormatter
     */
    public function getFormatter(string $currency, ?string $locale = null): \NumberFormatter;

    /**
     * @param string $currency
     * @param null|string $locale
     *
     * @return string
     */
    public function getSymbol(string $currency, ?string $locale = null): string;
}
