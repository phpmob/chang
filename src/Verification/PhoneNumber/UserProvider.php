<?php

declare(strict_types=1);

namespace Chang\Verification\PhoneNumber;

use Sylius\Bundle\UserBundle\Provider\AbstractUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider extends AbstractUserProvider
{
    /**
     * {@inheritdoc}
     */
    protected function findUser(string $uniqueIdentifier): ?UserInterface
    {
        if (filter_var($uniqueIdentifier, FILTER_VALIDATE_EMAIL)) {
            return $this->userRepository->findOneByEmail($uniqueIdentifier);
        }

        if (is_numeric($uniqueIdentifier) && 10 === strlen($uniqueIdentifier)) {
            return $this->userRepository->findOneBy(['phoneNumber' => $uniqueIdentifier]);
        }

        return $this->userRepository->findOneBy(['usernameCanonical' => $uniqueIdentifier]);
    }
}
