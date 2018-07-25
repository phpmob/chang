<?php

declare(strict_types=1);

namespace Chang\Security\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SecurityLoginType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', TextType::class, [
                'label' => 'chang.form.login.username',
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'chang.form.login.password',
            ])
            ->add('_remember_me', CheckboxType::class, [
                'label' => 'chang.form.login.remember_me',
                'required' => false,
            ])
        ;
    }
}
