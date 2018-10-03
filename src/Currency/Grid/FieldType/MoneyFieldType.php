<?php

declare(strict_types=1);

namespace Chang\Currency\Grid\FieldType;

use Chang\Grid\FieldType\AbstractLinkableFieldType;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Grid\DataExtractor\DataExtractorInterface;
use Sylius\Component\Grid\Definition\Field;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MoneyFieldType extends AbstractLinkableFieldType
{
    /**
     * @var MoneyFormatterInterface
     */
    private $formatter;

    public function __construct(DataExtractorInterface $dataExtractor, UrlGeneratorInterface $generator, MoneyFormatterInterface $formatter)
    {
        $this->formatter = $formatter;

        parent::__construct($dataExtractor, $generator);
    }

    /**
     * {@inheritdoc}
     */
    public function render(Field $field, $data, array $options)
    {
        $value = intval($this->dataExtractor->get($field, $data));
        $field->setOptions($options);

        $formatted = $this->formatter->format($value, $options['currency']);
        $value = sprintf('<span class="text-color-%s">%s%s</span>',
            $value < 0 ? 'red' : 'green',
            $options['valued_symbol'] && $value > 0 ? ($value < 0 ? '-' : '+') : '',
            $formatted
        );

        return $this->link($value, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'align' => 'right',
            'valued_symbol' => false,
            'currency' => null,
        ]);

        $resolver->setAllowedTypes('valued_symbol', ['boolean']);
        $resolver->setAllowedTypes('currency', ['string', 'null']);
    }
}
