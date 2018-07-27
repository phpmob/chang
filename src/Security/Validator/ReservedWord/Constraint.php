<?php

declare(strict_types=1);

namespace Chang\Security\Validator\ReservedWord;

use Symfony\Component\Validator\Constraint as BaseConstraint;

class Constraint extends BaseConstraint
{
    public $message = 'This username ("%value%") was reserved.';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'reserved_word_validator';
    }
}
