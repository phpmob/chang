<?php

declare(strict_types=1);

namespace Chang\Grid\FieldType;

use Sylius\Component\Grid\DataExtractor\DataExtractorInterface;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractLinkableFieldType implements FieldTypeInterface
{
    /**
     * @var DataExtractorInterface
     */
    protected $dataExtractor;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public function __construct(DataExtractorInterface $dataExtractor, UrlGeneratorInterface $generator)
    {
        $this->dataExtractor = $dataExtractor;
        $this->generator = $generator;
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function link($value, array $options)
    {
        $return = is_string($value) ? $value : (is_array($value) ? implode(',', $value) : $value);

        if ($options['link']) {
            return sprintf('<a href="%s">%s</a>', $options['link'], $return);
        }

        if ($options['route']) {
            $url = $this->generator->generate($options['route'], $options['parameters'] ?? []);

            return sprintf('<a href="%s">%s</a>', $url, $return);
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'width' => 'auto',
            'align' => 'left',
            'link' => null,
            'route' => null,
            'parameters' => [],
        ]);

        $resolver->setAllowedTypes('align', 'string');
        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('link', ['string', 'null']);
        $resolver->setAllowedTypes('route', ['string', 'null']);
        $resolver->setAllowedTypes('parameters', ['array']);
    }
}
