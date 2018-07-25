<?php

declare(strict_types=1);

namespace Chang\Security\Validator\PasswordEnglish;

use Symfony\Component\Validator\Constraint as BaseConstraint;

class Constraint extends BaseConstraint
{
    public $message = 'Password MUST be only contains english letters.';
}
