<?php

declare(strict_types=1);

namespace Chang\Currency\Form\Extension;

use Chang\Currency\SymbolInterface;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneySymbolExtension extends AbstractTypeExtension
{
    /**
     * @var SymbolInterface
     */
    private $formatter;

    /**
     * @var string
     */
    private $baseCurrency;

    public function __construct(SymbolInterface $formatter, string $baseCurrency)
    {
        $this->formatter = $formatter;
        $this->baseCurrency = $baseCurrency;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['currency' => $this->baseCurrency]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $pattern = \str_replace('{{ widget }}', '*W*', $view->vars['money_pattern']);
        $patterns = \explode(' ', $pattern);

        if (1 === count($patterns)) {
            return;
        }

        $symbolPos = '*W*' === $patterns[0] ? 1 : 0;
        $patterns[$symbolPos] = $this->formatter->getSymbol($options['currency']);

        $view->vars['money_pattern'] = \str_replace('*W*', '{{ widget }}', \vsprintf('%s %s', $patterns));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return MoneyType::class;
    }
}
