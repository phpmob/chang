<?php

declare(strict_types=1);

namespace Chang\Verification\PhoneNumber\Form\Type;

use Chang\Verification\PhoneNumber\NumberAwareInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ResendType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('phoneNumber', HiddenType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var NumberAwareInterface $data */
        $data = $form->getData();
        $view->vars['resend_duration'] = ($data->getPhoneNumberRequestedAt() ?? new \DateTime())
            ->add(new \DateInterval($options['resend_ttl']))
            ->getTimestamp();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['data_class', 'resend_ttl']);

        $resolver->setDefault('constraints', [
            new Callback(function (NumberAwareInterface $data, ExecutionContextInterface $context) {
                /** @var FormInterface $form */
                $form = $context->getRoot();
                $ttl = $form->getConfig()->getOption('resend_ttl');

                if ($data->isPhoneNumberRequestExpired(new \DateInterval($ttl))) {
                    $context->addViolation('chang.verification.phone_number.cant_resend');
                }
            })
        ]);
    }
}
