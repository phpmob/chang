<?php

declare(strict_types=1);

namespace Chang\Messenger\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface HashStorageInterface extends ResourceInterface, TimestampableInterface
{
    /**
     * @param string $id
     */
    public function setId(string $id): void;
}
