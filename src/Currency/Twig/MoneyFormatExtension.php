<?php

declare(strict_types=1);

namespace Chang\Currency\Twig;

use Sylius\Bundle\MoneyBundle\Templating\Helper\FormatMoneyHelperInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

class MoneyFormatExtension extends \Twig_Extension
{
    /**
     * @var FormatMoneyHelperInterface
     */
    private $helper;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var string
     */
    private $baseCurrency;

    public function __construct(FormatMoneyHelperInterface $helper, LocaleContextInterface $localeContext, string $baseCurrency)
    {
        $this->helper = $helper;
        $this->localeContext = $localeContext;
        $this->baseCurrency = $baseCurrency;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('chang_money', [$this, 'formatAmount']),
        ];
    }

    /**
     * @param int $amount
     * @param string|null $currencyCode
     * @param string|null $localeCode
     *
     * @return string
     */
    public function formatAmount(int $amount, string $currencyCode = null, string $localeCode = null): string
    {
        return $this->helper->formatAmount($amount, $currencyCode ?? $this->baseCurrency, $localeCode ?? $this->localeContext->getLocaleCode());
    }
}
