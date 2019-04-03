<?php

declare(strict_types=1);

namespace Chang\Grid\FieldType;

use Sylius\Component\Grid\Definition\Field;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;

class TextFieldType extends AbstractLinkableFieldType
{
    /**
     * {@inheritdoc}
     */
    public function render(Field $field, $data, array $options)
    {
        try {
            $value = $this->dataExtractor->get($field, $data);
        } catch (UnexpectedTypeException $e) {
            $value = null;
        }

        $field->setOptions($options);

        return $this->link(is_string($value) ? $value : (is_array($value) ? implode(',', $value) : $value), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
    }
}
