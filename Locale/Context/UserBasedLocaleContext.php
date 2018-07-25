<?php

declare(strict_types=1);

namespace Chang\Locale\Context;

use Chang\Locale\Model\LocaleAwareInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserBasedLocaleContext implements LocaleContextInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode(): string
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            throw new LocaleNotFoundException();
        }

        $user = $token->getUser();

        if (!$user instanceof LocaleAwareInterface) {
            throw new LocaleNotFoundException();
        }

        if (!$user->getLocaleCode()) {
            throw new LocaleNotFoundException();
        }

        return $user->getLocaleCode();
    }
}
