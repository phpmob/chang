<?php

declare(strict_types=1);

namespace Chang\Security\Validator\ReservedWord;

use Symfony\Component\Validator\Constraint as BaseConstraint;
use Symfony\Component\Validator\ConstraintValidator as BaseConstraintValidator;
use ZxcvbnPhp\Zxcvbn;

class ConstraintValidator extends BaseConstraintValidator
{
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var WordProviderInterface
     */
    private $provider;

    public function __construct(bool $enabled = true, WordProviderInterface $provider = null)
    {
        $this->enabled = $enabled;
        $this->provider = $provider ?? new WordProvider();
    }

    /**
     * @param mixed $value
     * @param Constraint|BaseConstraint $constraint
     */
    public function validate($value, BaseConstraint $constraint)
    {
        if (false === $this->enabled || null === $value || '' === $value) {
            return;
        }

        if ($this->provider->match($value)) {
            $this->context->addViolation($constraint->message, [
                '%value%' => $value,
            ]);
        }
    }
}
