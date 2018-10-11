<?php

declare(strict_types=1);

namespace Chang\SecureArea;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserPasswordChecker implements UserPasswordCheckerInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(string $password): bool
    {
        if (empty($password) || !$user = $this->getUser()) {
            return false;
        }

        return $this->encoderFactory
            ->getEncoder($user)
            ->isPasswordValid($user->getPassword(), $password, $user->getSalt());
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?UserInterface
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!$user = $token->getUser()) {
            return null;
        }

        if ($user instanceof UserInterface) {
            return $user;
        }

        return null;
    }
}
