<?php

declare(strict_types=1);

namespace Chang\SecureArea;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserPasswordCheckerInterface
{
    /**
     * @param string $password
     *
     * @return bool
     */
    public function isValid(string $password): bool;

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface;
}
