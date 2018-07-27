<?php

declare(strict_types=1);

namespace Chang\Web\Form\Type;

use Sylius\Bundle\UserBundle\Form\Type\UserType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebUserType extends UserType
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
                'label' => 'chang.form.web_user.first_name',
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'chang.form.web_user.last_name',
            ])
            ->add('localeCode', LocaleType::class, [
                'required' => false,
                'label' => 'chang.form.web_user.locale_code',
            ])
            ->add('picture', WebUserPictureType::class, [
                'required' => false,
                'label' => 'chang.form.web_user.picture',
                'check_file_error' => $options['check_picture_error'],
            ])
            ->add('gender', ChoiceType::class, [
                'required' => false,
                'label' => 'chang.form.web_user.gender',
                'choices' => array_flip([
                    'm' => 'chang.form.web_user.gender_man',
                    'f' => 'chang.form.web_user.gender_female',
                ]),
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
