<?php

declare(strict_types=1);

namespace Chang\Security\Validator\PasswordStrength;

use Symfony\Component\Validator\Constraint as BaseConstraint;

class Constraint extends BaseConstraint
{
    public $messages = [
        0 => 'Risky password.',
        1 => 'Protection from throttled online attacks.',
        2 => 'Protection from unthrottled online attacks.',
        3 => 'Moderate protection from offline slow-hash scenario.',
        4 => 'Strong protection from offline slow-hash scenario.',
    ];

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'password_strength_validator';
    }
}
