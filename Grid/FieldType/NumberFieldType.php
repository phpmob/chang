<?php

declare(strict_types=1);

namespace Chang\Grid\FieldType;

use Sylius\Component\Grid\DataExtractor\DataExtractorInterface;
use Sylius\Component\Grid\Definition\Field;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NumberFieldType extends AbstractLinkableFieldType
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(DataExtractorInterface $dataExtractor, UrlGeneratorInterface $generator, \Twig_Environment $twig)
    {
        $this->twig = $twig;

        parent::__construct($dataExtractor, $generator);
    }

    /**
     * {@inheritdoc}
     */
    public function render(Field $field, $data, array $options)
    {
        if ('.' !== $field->getPath()) {
            $data = $this->dataExtractor->get($field, $data);
        }

        $field->setOptions($options);

        return $this->link(twig_localized_number_filter($data, $options['style'], $options['type']), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'width' => 'auto',
            'align' => 'center',
            'style' => 'decimal',
            'type' => 'default',
        ]);

        $resolver->setAllowedTypes('style', 'string');
        $resolver->setAllowedTypes('type', 'string');
    }
}
