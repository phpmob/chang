<?php

declare(strict_types=1);

namespace Chang\User\Form\Model;

use Chang\Validator\Data\DataTransferObjectInterface;
use Sylius\Component\User\Model\UserInterface;

class ChangeUser implements DataTransferObjectInterface
{
    /**
     * @var UserInterface
     */
    private $origin;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $username;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
        $this->origin = clone $user;

        $this->username = $user->getUsername();
        $this->email = $user->getEmail();
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param null|string $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return bool
     */
    public function isEmailChanged(): bool
    {
        return $this->origin->getEmail() !== ($this->email ?? $this->origin->getEmail());
    }

    /**
     * @return bool
     */
    public function isUsernameChanged(): bool
    {
        return $this->origin->getUsername() !== ($this->username ?? $this->origin->getUsername());
    }

    /**
     * @return bool
     */
    public function isUserChanged(): bool
    {
        return $this->isEmailChanged() || $this->isUsernameChanged();
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @return UserInterface
     */
    public function getOrigin(): UserInterface
    {
        return $this->origin;
    }

    /**
     * {@inheritdoc}
     */
    public function getWarpedObject()
    {
        return $this->user;
    }
}
