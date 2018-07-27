<?php

declare(strict_types=1);

namespace Chang\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ConvertRawDataFormExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            if (is_array($data) && $event->getForm()->isRoot()) {
                array_walk_recursive($data, function (&$value) {
                    if ('[]' === $value) {
                        $value = [];
                    }

                    if ('NULL' === $value) {
                        $value = null;
                    }
                });

                $event->setData($data);
            }
        });
    }
}
