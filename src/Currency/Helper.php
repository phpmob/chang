<?php

declare(strict_types=1);

namespace Chang\Currency;

use Sylius\Bundle\CurrencyBundle\Templating\Helper\CurrencyHelperInterface;
use Symfony\Component\Templating\Helper\Helper as BaseHelper;

if (!interface_exists(CurrencyHelperInterface::class)) {
    return;
}

class Helper extends BaseHelper implements CurrencyHelperInterface
{
    /**
     * @var SymbolInterface
     */
    private $symbol;

    public function __construct(SymbolInterface $symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * {@inheritdoc}
     */
    public function convertCurrencyCodeToSymbol(string $code): string
    {
        return $this->symbol->getSymbol($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'sylius_currency';
    }
}
