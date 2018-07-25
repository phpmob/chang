<?php

declare(strict_types=1);

namespace Chang\Grid\FieldType;

use Sylius\Component\Grid\DataExtractor\DataExtractorInterface;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StateMachineFieldType implements FieldTypeInterface
{
    /**
     * @var DataExtractorInterface
     */
    private $dataExtractor;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $defaultTemplate;

    /**
     * @param DataExtractorInterface $dataExtractor
     * @param \Twig_Environment $twig
     * @param string $defaultTemplate
     */
    public function __construct(DataExtractorInterface $dataExtractor, \Twig_Environment $twig, $defaultTemplate)
    {
        $this->dataExtractor = $dataExtractor;
        $this->twig = $twig;
        $this->defaultTemplate = $defaultTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Field $field, $data, array $options)
    {
        if ('.' !== $field->getPath() && $field->getName() !== $field->getPath()) {
            $data = $this->dataExtractor->get($field, $data);
        }

        if ('state' === $options['property'] && 'state' !== $field->getName()) {
            $options['property'] = $field->getName();
        }

        $field->setOptions($options);

        return $this->twig->render($options['template'], ['data' => $data, 'options' => $options]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('template', $this->defaultTemplate);
        $resolver->setAllowedTypes('template', 'string');

        $resolver->setDefined('vars');
        $resolver->setAllowedTypes('vars', 'array');

        $resolver->setDefaults([
            'width' => 'auto',
            'align' => 'left',
            'type' => 'label',
            'property' => 'state',
            'route' => null,
            'method' => 'PATCH',
        ]);

        $resolver->setRequired('graph');

        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('align', 'string');
        $resolver->setAllowedValues('type', ['label', 'action']);
        $resolver->setAllowedTypes('property', 'string');
        $resolver->setAllowedTypes('route', ['null', 'string']);
        $resolver->setAllowedTypes('method', 'string');
    }
}
