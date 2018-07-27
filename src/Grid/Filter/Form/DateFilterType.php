<?php

declare(strict_types=1);

namespace Chang\Grid\Filter\Form;

use Sylius\Bundle\GridBundle\Form\DataTransformer\DateTimeFilterTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DateFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['from']) {
            $builder
                ->add('from', DateTimeType::class, [
                    'label' => 'sylius.ui.from',
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'required' => false,
                ]);
            $builder->get('from')->addViewTransformer(new DateTimeFilterTransformer('from'));
        }

        if ($options['to']) {
            $builder
                ->add('to', DateTimeType::class, [
                    'label' => 'sylius.ui.to',
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'required' => false,
                ]);
            $builder->get('to')->addViewTransformer(new DateTimeFilterTransformer('to'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
                'from' => true,
                'to' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'chang_grid_filter_xdate';
    }
}
