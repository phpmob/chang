<?php

declare(strict_types=1);

namespace Chang\Admin\Model;

use Sylius\Component\User\Model\User;

class AdminUser extends User implements AdminUserInterface
{
    /**
     * @var string
     */
    protected $localeCode;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $recipientHash;

    /**
     * @var AdminUserPictureInterface
     */
    protected $picture;

    /**
     * @var string
     */
    private $displayName;

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocaleCode(?string $localeCode): void
    {
        $this->localeCode = $localeCode;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName(): string
    {
        if ($this->displayName) {
            return $this->displayName;
        }

        $fullName = $this->getFullName();

        return $this->displayName = (string)($fullName ? $fullName : $this->username);
    }

    /**
     * {@inheritdoc}
     */
    public function getPicture(): ?AdminUserPictureInterface
    {
        return $this->picture;
    }

    /**
     * {@inheritdoc}
     */
    public function setPicture(?AdminUserPictureInterface $picture): void
    {
        $this->picture = $picture ? ($picture->getFile() ? $picture : null) : null;

        if ($this->picture) {
            $picture->setOwner($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFileBasePath()
    {
        return '/private/admins';
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipientHash(): ?string
    {
        return $this->recipientHash;
    }

    /**
     * {@inheritdoc}
     */
    public function setRecipientHash(?string $hash): void
    {
        $this->recipientHash = $hash;
    }
}
