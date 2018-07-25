<?php

declare(strict_types=1);

namespace Chang\Security\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleRegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $usernameField = $options['username']['field'] ?? 'username';
        $usernameType = $options['username']['type'] ?? TextType::class;
        $usernameTypeOptions = $options['username']['options'] ?? [];

        $builder
            ->add($usernameField, $usernameType, array_replace_recursive([
                'required' => true,
                'label' => 'chang.form.user.' . $usernameField,
            ], $usernameTypeOptions))
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'chang.form.user.password_first'],
                'second_options' => ['label' => 'chang.form.user.password_second'],
                'invalid_message' => 'chang.user.password.mismatch',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'username' => [
                'field' => 'username',
                'type' => TextType::class,
                'options' => [],
            ],
        ]);

        $resolver->setRequired(['data_class', 'validation_groups']);
    }
}
