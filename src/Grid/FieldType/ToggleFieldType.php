<?php

declare(strict_types=1);

namespace Chang\Grid\FieldType;

use Sylius\Component\Grid\DataExtractor\DataExtractorInterface;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToggleFieldType implements FieldTypeInterface
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

        if ('enabled' === $options['property'] && 'enabled' !== $field->getName() && '.' !== $field->getPath()) {
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
            'cls' => 'x-grid-table-column-actions',
            'width' => '100px',
            'align' => 'center',
            'property' => 'enabled',
            'method' => 'PATCH',
            'form' => 'toggle_resource',
            'btn_css' => 'btn btn-outline-secondary',
            'on_color' => 'success',
            'off_color' => 'danger',
            'on_label' => 'chang.ui.on',
            'off_label' => 'chang.ui.off',
            'on_value' => 1,
            'off_value' => 0,
            'labeled' => true,
        ]);

        $resolver->setRequired('route');
        $resolver->setRequired('parameters');

        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('align', 'string');
        $resolver->setAllowedTypes('property', 'string');
        $resolver->setAllowedTypes('route', ['string']);
        $resolver->setAllowedTypes('parameters', ['array']);
        $resolver->setAllowedTypes('on_color', 'string');
        $resolver->setAllowedTypes('off_color', 'string');
        $resolver->setAllowedTypes('on_label', 'string');
        $resolver->setAllowedTypes('off_label', 'string');
        $resolver->setAllowedTypes('on_value', 'int');
        $resolver->setAllowedTypes('off_value', 'int');
        $resolver->setAllowedTypes('btn_css', 'string');
        $resolver->setAllowedTypes('form', 'string');
        $resolver->setAllowedTypes('labeled', 'boolean');
    }
}
