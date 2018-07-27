<?php

declare(strict_types=1);

namespace Chang\Validator\Constraints;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class DataTransferObjectUniqueEntity extends UniqueEntity
{
    public $service = 'chang.orm.validator.dto_unique';
}
