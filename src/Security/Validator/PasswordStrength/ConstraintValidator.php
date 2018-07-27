<?php

declare(strict_types=1);

namespace Chang\Security\Validator\PasswordStrength;

use Symfony\Component\Validator\Constraint as BaseConstraint;
use Symfony\Component\Validator\ConstraintValidator as BaseConstraintValidator;
use ZxcvbnPhp\Zxcvbn;

class ConstraintValidator extends BaseConstraintValidator
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var int
     */
    private $score;

    /**
     * @var string
     */
    private $algorithm;

    public function __construct(string $algorithm, int $score = 1, array $options = [])
    {
        $this->algorithm = \strtolower($algorithm);
        $this->score = $score;
        $this->options = $options;
    }

    /**
     * @param mixed $value
     * @param Constraint|BaseConstraint $constraint
     */
    public function validate($value, BaseConstraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if ('zxcvbn' === $this->algorithm) {
            $result = (new Zxcvbn())->passwordStrength($value, $this->options);
        } else {
            $result = $this->basicStrength($value);
        }

        if ($result['score'] < $this->score) {
            $this->context->addViolation($constraint->messages[$result['score']]);
        }
    }

    /**
     * @param $value
     *
     * @return array
     */
    private function basicStrength($value): array
    {
        $score = 0;

        if (\strlen($value) > 4) {
            $score++;
        }

        if (\preg_match('/[a-z]/', $value) && \preg_match('/[A-Z]/', $value)) {
            $score++;
        }

        if (\preg_match('/\d+/', $value)) {
            $score++;
        }

        // none word
        if (\preg_match('/\W+/', $value)) {
            $score++;
        }

        return ['score' => $score];
    }
}
