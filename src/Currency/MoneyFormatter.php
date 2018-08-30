<?php

declare(strict_types=1);

namespace Chang\Currency;

use Webmozart\Assert\Assert;

class MoneyFormatter implements MoneyFormatterInterface
{
    /**
     * @var SymbolInterface
     */
    private $symbol;

    /**
     * @var DivisorInterface
     */
    private $divisor;

    /**
     * @var string
     */
    private $baseCurrency;

    public function __construct(SymbolInterface $symbol, DivisorInterface $divisorScale, string $baseCurrency)
    {
        $this->symbol = $symbol;
        $this->divisor = $divisorScale;
        $this->baseCurrency = $baseCurrency;
    }

    /**
     * {@inheritdoc}
     */
    public function format(int $amount, string $currency = null, ?string $locale = null): string
    {
        $currency = strtoupper($currency ?? $this->baseCurrency);
        $formatter = $this->symbol->getFormatter($currency, $locale);
        $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $this->divisor->getScaleLength());

        $result = $formatter->formatCurrency(abs($this->divisor->getDivided($amount)), $currency);

        Assert::notSame(
            false,
            $result,
            sprintf('The amount "%s" of type %s cannot be formatted to currency "%s".', $amount, gettype($amount), $currency)
        );

        // FIXME: remove when find the way set symbol in php-intl ext.
        $symbol = $this->symbol->getSymbol($currency, $locale);
        $result = str_replace($currency, $symbol, $result);

        preg_match('/([^\d,.]+)([\d,.]+)/', $result, $match);

        // no symbol, insert empty symbol
        if (empty($match)) {
            $match[1] = '';
            $match[2] = $result;
        }

        // eliminate 0 tailing
        $numbers = explode('.', (string)(1 * floatval(str_replace(',', '', $match[2]))));
        $number = number_format((float)$numbers[0]);

        if (2 === count($numbers)) {
            $number .= '.' . $numbers[1];
        }

        $result = $match[1] . $number;

        return $amount >= 0 ? $result : '-' . $result;
    }
}
