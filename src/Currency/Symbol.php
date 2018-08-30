<?php

declare(strict_types=1);

namespace Chang\Currency;

use Sylius\Component\Locale\Context\LocaleContextInterface;

class Symbol implements SymbolInterface
{
    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var string[]
     */
    private $symbols = [
        'THB' => '฿',
    ];

    public function __construct(LocaleContextInterface $localeContext, array $symbols = [])
    {
        $this->localeContext = $localeContext;
        $this->symbols = array_merge($this->symbols, array_change_key_case($symbols, CASE_UPPER));
    }

    /**
     * @return string
     */
    private function getLocaleCode(): string
    {
        return $this->localeContext->getLocaleCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatter(string $currency, ?string $locale = null): \NumberFormatter
    {
        $formatter = new \NumberFormatter($locale ?? $this->getLocaleCode(), \NumberFormatter::CURRENCY);
        $symbol = array_key_exists($currency, $this->symbols) ? $this->symbols[$currency] : null;

        if (null !== $symbol) {
            // FIXME: not work in php-intl ext.
            $formatter->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $symbol);
            $formatter->setSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL, $symbol);
        }

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function getSymbol(string $currency, ?string $locale = null): string
    {
        $symbol = array_key_exists($currency, $this->symbols) ? $this->symbols[$currency] : null;

        return $symbol ?? $this->getFormatter($currency, $locale)->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }
}
