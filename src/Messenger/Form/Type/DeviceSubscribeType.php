<?php

declare(strict_types=1);

namespace Chang\Messenger\Form\Type;

use Chang\Messenger\Model\DeviceInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceSubscribeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('token', TextType::class, [
                'required' => true,
                'label' => 'chang.messenger.form.device.token',
            ])
            ->add('platform', ChoiceType::class, [
                'required' => true,
                'label' => 'chang.messenger.form.device.platform',
                'choices' => DeviceInterface::SupportedPlatforms,
            ])
            ->add('metas', CollectionType::class, [
                'required' => false,
                'allow_add' => true,
                'label' => 'chang.messenger.form.device.meta',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['chang.messenger'],
        ]);

        $resolver->setRequired('data_class');
    }
}
