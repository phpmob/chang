<?php

declare(strict_types=1);

namespace Chang\Grid\FieldType;

use Sylius\Component\Grid\DataExtractor\DataExtractorInterface;
use Sylius\Component\Grid\Definition\Field;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LocalizedDateFieldType extends AbstractLinkableFieldType
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

        return $data ? $this->link(twig_localized_date_filter(
            $this->twig,
            $data,
            $options['dateFormat'],
            $options['timeFormat'],
            $options['locale'],
            $options['timezone']
        ), $options) : '';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'width' => '200px',
            'align' => 'left',
            'dateFormat' => 'medium',
            'timeFormat' => 'short',
            'locale' => null,
            'timezone' => null,
        ]);

        $resolver->setAllowedTypes('align', 'string');
        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('dateFormat', 'string');
        $resolver->setAllowedTypes('timeFormat', 'string');
        $resolver->setAllowedTypes('locale', ['string', 'null']);
        $resolver->setAllowedTypes('timezone', ['string', 'null']);
    }
}
