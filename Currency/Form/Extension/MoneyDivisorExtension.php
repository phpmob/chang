<?php

declare(strict_types=1);

namespace Chang\Currency\Form\Extension;

use Chang\Currency\DivisorInterface;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyDivisorExtension extends AbstractTypeExtension
{
    /**
     * @var DivisorInterface
     */
    private $divisor;

    public function __construct(DivisorInterface $divisor)
    {
        $this->divisor = $divisor;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['value'] = 1 * floatval($view->vars['value']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'divisor' => $this->divisor->getScale(),
            'scale' => $this->divisor->getScaleLength(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return MoneyType::class;
    }
}
