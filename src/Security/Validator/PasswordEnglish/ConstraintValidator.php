<?php

declare(strict_types=1);

namespace Chang\Security\Validator\PasswordEnglish;

use Symfony\Component\Validator\Constraint as BaseConstraint;
use Symfony\Component\Validator\ConstraintValidator as BaseConstraintValidator;

class ConstraintValidator extends BaseConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|BaseConstraint $constraint
     */
    public function validate($value, BaseConstraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (\strlen($value) !== \mb_strlen($value)) {
            $this->context->addViolation($constraint->message);
        }
    }
}
