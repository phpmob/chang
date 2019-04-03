<?php

declare(strict_types=1);

namespace Chang\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AutocompleteChoiceType extends ResourceAutocompleteChoiceType
{
    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'chang_autocomplete_choice';
    }
}
