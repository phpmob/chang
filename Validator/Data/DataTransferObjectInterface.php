<?php

declare(strict_types=1);

namespace Chang\Validator\Data;

interface DataTransferObjectInterface
{
    /**
     * @return object|null
     */
    public function getWarpedObject();
}
