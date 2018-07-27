<?php

declare(strict_types=1);

namespace Chang\Verification\PhoneNumber\Form\Type;

use Chang\Verification\PhoneNumber\NumberAwareInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ValidateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phoneNumberUserToken', TextType::class, [
                'required' => true,
                'label' => 'chang.form.user.phone_number_user_token',
                'constraints' => [
                    new NotBlank(['message' => 'chang.verification.phone_number.user_token.not_blank']),
                ]
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                if (0 === $event->getForm()->getErrors(true)->count()) {
                    /** @var NumberAwareInterface $data */
                    $data = $event->getData();
                    $data->setPhoneNumberVerifiedAt(new \DateTime());
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['data_class']);

        $resolver->setDefault('constraints', [
            new Callback(function (NumberAwareInterface $data, ExecutionContextInterface $context) {
                if ($data->getPhoneNumberUserToken() !== $data->getPhoneNumberVerificationToken()) {
                    $context
                        ->buildViolation('chang.verification.phone_number.user_token.not_valid')
                        ->atPath('phoneNumberUserToken')
                        ->addViolation();
                }
            })
        ]);
    }
}
