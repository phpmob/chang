<?php

declare(strict_types=1);

namespace Chang\Admin\Form\Type;

use Sylius\Bundle\UserBundle\Form\Type\UserType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends UserType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('plainPassword')
            ->remove('enabled')
            ->add('plainPassword', PasswordType::class, [
                'label' => 'sylius.form.user.password.label',
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.form.user.enabled',
                'required' => false,
            ])
            ->add('firstName', TextType::class, [
                'required' => false,
                'label' => 'chang.form.admin_user.first_name',
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'chang.form.admin_user.last_name',
            ])
            ->add('localeCode', LocaleType::class, [
                'required' => false,
                'label' => 'chang.form.admin_user.locale_code',
            ])
            ->add('picture', AdminUserPictureType::class, [
                'required' => false,
                'label' => 'chang.form.admin_user.picture',
                'check_file_error' => $options['check_picture_error']
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('check_picture_error', true);
    }
}
