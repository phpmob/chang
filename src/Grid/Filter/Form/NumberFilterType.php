<?php

declare(strict_types=1);

namespace Chang\Grid\Filter\Form;

use Chang\Grid\Filter\NumberFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class NumberFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!isset($options['type'])) {
            $builder
                ->add('type', ChoiceType::class, [
                    'choices' => [
                        'sylius.ui.equal' => NumberFilter::TYPE_EQUAL,
                        'sylius.ui.not_equal' => NumberFilter::TYPE_NOT_EQUAL,
                        'sylius.ui.empty' => NumberFilter::TYPE_EMPTY,
                        'sylius.ui.not_empty' => NumberFilter::TYPE_NOT_EMPTY,
                        'sylius.ui.in' => NumberFilter::TYPE_IN,
                        'sylius.ui.not_in' => NumberFilter::TYPE_NOT_IN,
                        'sylius.ui.between' => NumberFilter::TYPE_BETWEEN,
                        'sylius.ui.less_than' => NumberFilter::TYPE_LESS_THAN,
                        'sylius.ui.less_than_or_equal' => NumberFilter::TYPE_LESS_THAN_OR_EQUAL,
                        'sylius.ui.greater_than' => NumberFilter::TYPE_GREATER_THAN,
                        'sylius.ui.greater_than_or_equal' => NumberFilter::TYPE_GREATER_THAN_OR_EQUAL,
                    ],
                ])
            ;
        }

        $builder
            ->add('value', IntegerType::class, [
                'required' => false,
                'label' => 'sylius.ui.value',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
            ])
            ->setDefined('type')
            ->setAllowedValues('type', [
                NumberFilter::TYPE_LESS_THAN,
                NumberFilter::TYPE_LESS_THAN_OR_EQUAL,
                NumberFilter::TYPE_GREATER_THAN,
                NumberFilter::TYPE_GREATER_THAN_OR_EQUAL,
                NumberFilter::TYPE_BETWEEN,
                NumberFilter::TYPE_EQUAL,
                NumberFilter::TYPE_NOT_EQUAL,
                NumberFilter::TYPE_EMPTY,
                NumberFilter::TYPE_NOT_EMPTY,
                NumberFilter::TYPE_IN,
                NumberFilter::TYPE_NOT_IN
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'chang_grid_filter_number';
    }
}
