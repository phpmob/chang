<?php

declare(strict_types=1);

namespace Chang\Validator\Constraints;

use Chang\Validator\Data\DataTransferObjectInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class DataTransferObjectUniqueEntityValidator extends ConstraintValidator
{
    /**
     * @var UniqueEntityValidator
     */
    private $validator;

    public function __construct(UniqueEntityValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param mixed|DataTransferObjectInterface $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value)) {
            return;
        }

        Assert::implementsInterface($value, DataTransferObjectInterface::class);

        $this->validator->validate($value->getWarpedObject(), $constraint);
    }
}
