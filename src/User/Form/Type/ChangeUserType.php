<?php

declare(strict_types=1);

namespace Chang\User\Form\Type;

use Chang\User\Form\Model\ChangeUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'sylius.form.user.username',
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'sylius.form.user.email',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangeUser::class,
            'validation_groups' => [
                'chang_user_username',
                'chang_user_email',
                'chang_user',
            ],
        ]);
    }
}
